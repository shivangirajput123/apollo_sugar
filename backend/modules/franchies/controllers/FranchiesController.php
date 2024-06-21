<?php

namespace backend\modules\franchies\controllers;

use Yii;
use backend\modules\franchies\models\Franchies;
use backend\modules\franchies\models\FranchiesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
/**
 * FranchiesController implements the CRUD actions for Franchies model.
 */
class FranchiesController extends Controller
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
     * Lists all Franchies models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FranchiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	
	public function actionReferals($id)
    {
		$curl = curl_init();
        $data = array();
		$user = User::find()->where(['id'=>$id])->one();
		$url = Yii::$app->request->hostInfo.'/SugarFranchies/frontend/web/index.php?r=site/referrallist&id='.$id;
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		   'Content-Type: application/json',
		   'Authorization: Bearer ' . $user->access_token
		));
		curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl),true);
		//print_r($output);exit;
		$data = $output['data'];
		//print_r($data);exit;
        curl_close($curl);	
				
        $provider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 10,
                ],
        ]);
		//print_r($provider);exit;
        return $this->render('referals', [
            'referals' => $provider
        ]);
    }
	
    /**
     * Displays a single Franchies model.
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
     * Creates a new Franchies model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Franchies();
		$model->scenario = 'create';
		$model->cities = City::getCities();
		$model->locations = [];
        if ($model->load(Yii::$app->request->post())) 
		{			
			$model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Franchies model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->cities = City::getCities();
		$user = User::find()->where(['id'=>$model->userId])->one();
		$model->email = $user->email;
		$model->locations = Location::getLocationsByID($model->cityId);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Franchies model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		$model = Franchies::findOne($id);
        $model->Status = 'In-active';
		$model->save();
		$user = User::find()->where(['id'=>$model->userId])->one();
		$user->status = 9;
		$user->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Franchies model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Franchies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Franchies::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
