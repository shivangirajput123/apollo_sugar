<?php

namespace backend\modules\clinics\controllers;

use Yii;
use backend\modules\clinics\models\Clinics;
use backend\modules\clinics\models\ClinicsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
use yii\web\UploadedFile;
use yii\helpers\Json;
use frontend\models\Userplans;
use backend\modules\users\models\Dietician;
use backend\modules\users\models\Doctors;
use backend\modules\packages\models\Plandetails;
use backend\modules\packages\models\Plans;
/**
 * ClinicsController implements the CRUD actions for Clinics model.
 */
class ClinicsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Clinics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClinicsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionPlanapproval()
    {
        $searchModel = new ClinicsSearch();
        $dataProvider = $searchModel->approval(Yii::$app->request->queryParams);

        return $this->render('planapproval', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Clinics model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
		$createdBy = User::find()->where(['id'=>$model->createdBy])->one();
		$updatedBy = User::find()->where(['id'=>$model->updatedBy])->one();
		$model->createdBy = $createdBy->username;
		$model->updatedBy = $updatedBy->username;
		$model->createdDate = date('d-M-Y',strtotime($model->createdDate ));
		$model->updatedDate = date('d-M-Y',strtotime($model->updatedDate ));
        return $this->render('view', [
            'model' => $model,
        ]);
    }
	
	public function actionApproval($id)
    {
        $query = UserPlans::find()
              ->select(['i.username','userplans.price','userplans.userPlanId','userplans.createdDate','p.planName','up.firstName'])
			  ->innerJoin(['i' => 'user'],'`i`.`access_token` = `userplans`.`access_token`')
			  ->innerJoin(['up' => 'userprofile'],'`up`.`access_token` = `userplans`.`access_token`')
			  ->leftJoin(['p' => 'plans'],'`p`.`planId` = `userplans`.`planId`')->where(['userplans.userPlanId'=>$id])->asArray()->one();	
		//print_r($query);exit;
		$newmodel = UserPlans::find()->where(['userPlanId'=>$id])->one();
		$newmodel->doctors = Doctors::getDoctorsByClinicsid(Yii::$app->user->id);
		$newmodel->dieticians = Dietician::getDieticianByClinicsid(Yii::$app->user->id);		
		if ($newmodel->load(Yii::$app->request->post()) ) 
		{
			$plan = Plans::find()->where(['planId'=>$newmodel->planId])->one();
			$clinic = Clinics::find()->where(['userId'=>Yii::$app->user->id])->one();
			if(empty($newmodel->doctorId) || $newmodel->doctorId == 0)
			{
				$users = Doctors::find()->where(['clinicId'=>$clinic->clinicId,'Status'=>'Active'])->all();
				foreach($users as $key=>$value)
				{
						$doctorplan = Userplans::find()->where(['doctorId'=>$value->userId])->count();
						if($doctorplan == 0)
						{
							$newmodel->doctorId = $value->userId;
						}
						else
						{
							$doctorplan = "select  userplans.doctorId,count(*) as total  FROM userplans LEFT JOIN doctors ON doctors.userId = userplans.doctorId where userplans.Status='Subcribed' AND doctors.clinicId='".$clinic->clinicId."' AND doctors.Status='Active' GROUP BY userplans.doctorId ORDER BY count(*) ASC";
							$model = Userplans::findBySql($doctorplan)->asArray()->all();
							$newmodel->doctorId = $model[0]['doctorId'];
						}
			   }			   
			}
			
			if(empty($newmodel->dieticianId) || $newmodel->dieticianId == 0)
			{
				$dieticians = Dietician::find()->where(['clinicId'=>$clinic->clinicId,'Status'=>'Active'])->all();
				foreach($dieticians as $dkey=>$dvalue)
				{
						$dietplan = Userplans::find()->where(['dieticianId'=>$dvalue->userId])->count();
						
						if($dietplan == 0)
						{
							$newmodel->dieticianId = $dvalue->userId;
						}
						else
						{
							$dietplan = "select  userplans.dieticianId,count(*) as total  FROM userplans LEFT JOIN dietician ON dietician.userId = userplans.dieticianId where userplans.Status='Subcribed' AND dietician.clinicId='".$clinic->clinicId."' AND dietician.Status='Active' GROUP BY userplans.dieticianId ORDER BY count(*) ASC";
							$model = Userplans::findBySql($dietplan)->asArray()->all();
							//print_r($model);exit;
							$newmodel->dieticianId = $model[0]['dieticianId'];
						}
				}			   
			}
			
			$doctorplancount = Plandetails::find()->where(['planId'=>$newmodel->planId,'text'=>1])->count();
			$dietianplancount = Plandetails::find()->where(['planId'=>$newmodel->planId,'text'=>2])->count();
			if($doctorplancount == 0 && $plan->unlimdoctorcons == 0)
			{
					$newmodel->doctorId = 0;
			}
			if($dietianplancount == 0 && $plan->unlimdiecticiancons == 0)
			{
					$newmodel->dieticianId = 0;
			}			
			//print_r($newmodel->dieticianId);exit;
			$Plandetails = Plandetails::find()->where(['planId'=>$newmodel->planId])->orderBy('plandetailId DESC')->one();
			$newmodel->Status = "Subcribed";
			$newmodel->updatedDate = date('Y-m-d');
			$newmodel->planExpiryDate =  date('Y-m-d',strtotime("+".$Plandetails->endday." day", strtotime(date('Y-m-d'))));					
			if(empty($newmodel->clinicId))
			{
				$newmodel->clinicId = Yii::$app->user->id;
			}
			//print_r($newmodel->txnId);exit;
			$newmodel->save();
			return $this->redirect(['planapproval']);
        }
		return $this->render('approval', [
            'model' => $query,
			'newmodel' => $newmodel,
        ]);
    }

    /**
     * Creates a new Clinics model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Clinics();
		$model->cities = City::getCities();
		$model->locations = [];
		$model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->save())
		{
			$model->profileImage = UploadedFile::getInstance($model,'profileImage');
            if(!empty($model->profileImage))
            {
                $imageName = time().$model->profileImage->name;
                $model->profileImage->saveAs(Yii::getAlias('@backend/web/images/profilepictures/').$imageName );
                $model->profileImage = 'images/profilepictures/'.$imageName;               
            }
            else
			{
                $model->profileImage = '';
            }
           return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Clinics model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->cities = City::getCities();
		$model->locations = Location::getLocationsByID($model->cityId);
		$profileimage = $model->profileImage;
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
			$model->profileImage = UploadedFile::getInstance($model,'profileImage');			
            if(!empty($model->profileImage))
            {
                $imageName = time().$model->profileImage->name;
                $model->profileImage->saveAs(Yii::getAlias('@backend/web/images/profilepictures/').$imageName );
                $model->profileImage = 'images/profilepictures/'.$imageName;               
            }
            else
			{
                $model->profileImage = $profileimage;
            }
			//print_r($model->profileImage);exit;
           return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Clinics model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Clinics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Clinics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Clinics::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	public function actionSubclinic()
    {
        $out = [];
       // print_r($_POST);exit;
        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
            $cat_id = $parents[0];
            $out = Clinics::getClinics($cat_id);
            
            echo Json::encode(['output'=>$out, 'selected'=>'']);
            return;
            }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
            }
}
