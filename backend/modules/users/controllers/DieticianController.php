<?php

namespace backend\modules\users\controllers;

 
use backend\modules\users\models\Dietician;
use backend\modules\users\models\DieticianSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use yii\web\UploadedFile;
use common\models\User;
use backend\modules\users\models\Dslots;
use yii\web\Response;
use yii\widgets\ActiveForm;
use backend\modules\clinics\models\Clinics;
/**
 * DieticianController implements the CRUD actions for Dietician model.
 */
class DieticianController extends Controller
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
     * Lists all Dietician models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DieticianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dietician model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Dietician model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dietician();
		$model->scenario = 'create';
        $model->cities = City::getCities();
		$model->locations = [];
		$model->clinics = [];
		if(Yii::$app->user->identity->roleName == 'Clinic')
		{
			$clinic = Clinics::find()->where(['userId'=>Yii::$app->user->id])->one();
			$model->cityId =$clinic->cityId; 
			$model->locations = Location::getLocationsByID($model->cityId);
			$model->locationId =$clinic->stateId;
		    $model->clinics = Clinics::getClinicsByID($model->locationId);	
            $model->clinicId =$clinic->clinicId;			
			
		}
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$dietician = User::find()->where(['email'=>$model->email])->orWhere(['mobilenumber'=>$model->mobilenumber])->one();
			if(empty($dietician))
			{
				$model->dieticianName = $model->username;
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
				$model->save();
				return $this->redirect(['index']);
		    }
			else
			{
				$model->addError('email','Email Already Exist');
				return $this->render('create', [
					'model' => $model,
				]);
			}
        }       

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Dietician model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model = $this->findModel($id);
		$model->cities = City::getCities();
		$model->locations = Location::getLocationsByID($model->cityId);
		$model->clinics = Clinics::getClinicsByID($model->clinicId);
		$profileimage = $model->profileImage;
		$username = User::find()->where(['id'=>$model->userId])->one();
		$model->username = $username->username;
        if ($model->load(Yii::$app->request->post())) {
			$model->dieticianName = $model->username;
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
            $model->save();
            return $this->redirect(['index']);
        }

        

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Dietician model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Dietician::findOne($id);
		$model->Status = 'In-active';
		$model->save();
		$user = User::find()->where(['id'=>$model->userId])->one();
		$user->status = 9;
		$user->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Dietician model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dietician the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dietician::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	public function actionSlots($id){
		$model = new Dslots();
		$dietician = Dietician::find()->where(['dieticianId'=>$_GET['id']])->one();
		$model->timings = $dietician->timings;
		$model->duration = $dietician->duration;
		$slots = Dslots::find()->where(['dieticianId'=>$id,'slotDate'=>$_GET['date']])->asArray()->all();
		if($slots != [])
		{
			for($i=0;$i<count($slots);$i++)
			{
				$model->slots[$i]['slotId'] = $slots[$i]['slotId'];
				$model->slots[$i]['slotTime'] = $slots[$i]['slotTime'];
			}
		}
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
         	Yii::$app->response->format = Response::FORMAT_JSON;
         	if (!$model->validate()) { 
               	return ActiveForm::validate($model);
    		}
    		else
			{       
					$begin = strtotime($model->slotDate);
                    $end   = strtotime($model->endDate);
				   // print_r($begin);exit;
				   // print_r($model->slots);exit;
					for($i = $begin; $i <= $end; $i = $i + 86400){
					{
						$date = date('Y-m-d',$i);
						
						Dslots::deleteAll(['dieticianId'=>$id,'slotDate'=>$date]);
						for($k =0 ; $k < count($model->slots); $k++)
						{
							
								$newmodel = new Dslots();
								$newmodel->dieticianId = $id;
								$newmodel->slotDate = $date;
								$newmodel->slotTime = $model->slots[$k]['slotTime'];
								$newmodel->createdBy = Yii::$app->user->identity->id;
								$newmodel->createdDate =  date('Y-m-d H:i:s');
								$newmodel->updatedBy = Yii::$app->user->identity->id;
								$newmodel->updatedDate =  date('Y-m-d H:i:s');
								$newmodel->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
								$newmodel->save();
								//print_r($newmodel->errors);exit;						
						}
					}
									
			}
			$dietician->timings = $model->timings;
			$dietician->duration = $model->duration;
			$dietician->save();
			return $this->redirect(['index']);
		  }
			
		}

		return $this->render('slots', [
            'model' => $model,
        ]);
	
	}
	public function actionGetslots(){
		$data = '<table class="multiple-input-list table table-condensed table-renderer"><tbody>';
		
		$newtimings = explode(', ',$_GET['timings']);
		$times = [];
		$i = 0;
		foreach($newtimings as $key=>$value)
		{
		
			$times= explode('to',$value);	
			$starttime = date("H:i", strtotime($times[0]));
			$endtime = date("H:i", strtotime($times[1]));			
			$times[$i] = $starttime;
			$data = '<table class="multiple-input-list table table-condensed table-renderer"><tbody>';
			while($times[$i] <= $endtime)
			{
				$convert = date('h:iA', strtotime($times[$i]));
				
				$data .= '<tr class="multiple-input-list__item"><td class="list-cell__slotTime"><input type="hidden" id="slots-slots-0-slotid" name="Dslots[slots][0][slotId]" value=""><div class="field-slots-slots-0-slottime form-group"><input type="text" id="slots-slots-0-slottime" class="form-control" name="Dslots[slots]['.$i.'][slotTime]" value='.$convert.' style="width: 100%;">
							<div class="help-block help-block-error"></div></div></td>
							<td class="list-cell__button"></td></tr>';

				$i++;
				$times[$i] = date("H:i", strtotime('+'.$_GET['duration'].'minutes', strtotime($times[$i-1])));
			}
		}
		$data .= '</tbody></table>';
		echo $data;exit;
	}
}
