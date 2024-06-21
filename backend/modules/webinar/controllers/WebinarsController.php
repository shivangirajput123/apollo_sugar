<?php

namespace backend\modules\webinar\controllers;

use Yii;
use backend\modules\webinar\models\Webinars;
use backend\modules\webinar\models\WebinarsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use backend\modules\common\models\Specialties;
use common\models\User;
use backend\modules\users\models\Doctors;
use yii\helpers\Json;

/**
 * WebinarsController implements the CRUD actions for Webinars model.
 */
class WebinarsController extends Controller
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
     * Lists all Webinars models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WebinarsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Webinars model.
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

    /**
     * Creates a new Webinars model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Webinars();
		$model->cities = City::getCities();
		$model->locations = [];
		$model->doctors = Doctors::getDoctorsNew();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			
			if(date('H:i',strtotime($model->endtime)) < date('H:i',strtotime($model->starttime)))
			{
				$model->addError('endtime','Please Select end time greater than starttime');
				return $this->render('create', [
					'model' => $model,
				]);
				
			}
			else
			{
			$url = "https://agreements.apollohl.in/webinar/meetings/createMeeting";
			$ch = curl_init($url);
			$dataU = array(
            'firstName' => Yii::$app->user->identity->username,
            'lastName' => '',
            'email' => Yii::$app->user->identity->email,
            'mobile' => Yii::$app->user->identity->mobilenumber,
            'client' => 'mobile',
			'date' => $model->PublishDate,
            'time' => $model->time
			);
			$payload = json_encode($dataU);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resultDynamic = json_decode(curl_exec($ch));
		//	print_r($resultDynamic->data->meetingUrl);exit;
			$model->meetingUrl = $resultDynamic->data->meetingUrl;
			$model->PublishDate = date('Y-m-d',strtotime($model->PublishDate));
			$model->time = $model->starttime.' - '.$model->endtime;
			$model->save();
		   // 
            return $this->redirect(['index']);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Webinars model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->cities = City::getCities();
		//$model->locations = Location::getLocationsByID();
		$model->specialies = Specialties::getSpecialties();
		$model->doctors = Doctors::getDoctorsNew();
		$model->time = explode(' - ',$model->time);
		$model->starttime = $model->time[0];
		$model->endtime = $model->time[1];
		$model->PublishDate = date('dd/mm/yyyy',strtotime($model->PublishDate));
		//print_r();exit;
        if ($model->load(Yii::$app->request->post())) 
		{
			if(date('H:i',strtotime($model->endtime)) < date('H:i',strtotime($model->starttime)))
			{
				$model->addError('endtime','Please Select end time greater than starttime');
				return $this->render('update', [
					'model' => $model,
				]);
				
			}
			else
			{
				 $model->time = $model->starttime.' - '.$model->endtime;
				 $model->PublishDate = date('Y-m-d',strtotime($model->PublishDate));
				 $model->save();
				 return $this->redirect(['index']);
			}
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Webinars model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		if($model->Status == 'In-active')
		{
			$model->Status = 'Active';
		}
		else
		{
			$model->Status = 'In-active';
		}
	//	$model->Status = 'In-active';
		$model->save();
        return $this->redirect(['index']);
    }
	public function actionChangepublishstatus($id)
    {
        $model = $this->findModel($id);
		if($model->PublishStatus == 'Un-Publish')
		{
			$model->PublishStatus = 'Publish';
		}
		else
		{
			$model->PublishStatus = 'Un-Publish';
		}
		$model->save();
        return $this->redirect(['index']);
    }
    /**
     * Finds the Webinars model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Webinars the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Webinars::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	public function actionWebinars()
    {
        $out = [];
       // print_r($_POST);exit;
        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
            $cat_id = $parents[0];
            $out = Webinars::getWebinars($cat_id);
            
            echo Json::encode(['output'=>$out, 'selected'=>'']);
            return;
            }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
            }
}
