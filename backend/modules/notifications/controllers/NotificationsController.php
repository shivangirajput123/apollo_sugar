<?php

namespace backend\modules\notifications\controllers;

use Yii;
use backend\modules\notifications\models\Notifications;
use backend\modules\notifications\models\NotificationsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use backend\modules\notifications\models\Notificationtypes;
use backend\modules\webinar\models\Webinars;
use backend\modules\packages\models\Plans;
use frontend\models\Login;
/**
 * NotificationsController implements the CRUD actions for Notifications model.
 */
class NotificationsController extends Controller
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
     * Lists all Notifications models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notifications model.
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
     * Creates a new Notifications model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Notifications();
		$model->cities = City::getCities();
		$model->notificationtypes = Notificationtypes::getNotificationtypes();
		$model->locations = [];
		$model->webinars = Webinars::getWebinarsByID();
		$model->programs = Plans::getPlans();
		if(isset($_GET['name'])&& !empty($_GET['name'])){
			$model->title = $_GET['name'];
		}
        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
		{	
			$data = [];
			$logins = Login::find()->all();
			foreach($logins as $key=>$value)
			{
				$fcm_id[] = $value->gcm_id;
			}	
			$data['title'] = $model->title;
			$data['body'] = $model->title;
			$send = $model->addUserFcm($data, $fcm_id);	
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Notifications model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->webinars = Webinars::getWebinarsByID();
		$model->programs = Plans::getPlans();
        if ($model->load(Yii::$app->request->post())) {
            $data = [];
			$logins = Login::find()->all();
			foreach($logins as $key=>$value)
			{
				$fcm_id[] = $value->gcm_id;
			}	
			$data['title'] = $model->title;
			$data['body'] = $model->title;
			$send = $model->addUserFcm($data, $fcm_id);	
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Notifications model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Notifications::findOne($id);
		$model->Status = 'In-active';
		$model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Notifications model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notifications the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notifications::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
