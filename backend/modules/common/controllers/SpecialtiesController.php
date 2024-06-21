<?php

namespace backend\modules\common\controllers;

use Yii;
use backend\modules\common\models\Specialties;
use backend\modules\common\models\SpecialtiesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
use yii\helpers\Json;
/**
 * SpecialtiesController implements the CRUD actions for Specialties model.
 */
class SpecialtiesController extends Controller
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
     * Lists all Specialties models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpecialtiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Specialties model.
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
     * Creates a new Specialties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Specialties();
		$model->cities = City::getCities();
		$model->locations = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Specialties model.
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
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Specialties model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		$model->Status = 'In-active';
		$model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Specialties model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Specialties the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Specialties::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	public function actionSpecialities()
    {
        $model = Specialties::find()->where(['locationId' => $_GET['location'],'Status'=>'Active'])->orderBy('speciality_id DESC')->asArray()->all();
        $data ='<option>select option</option>';
        foreach($model as $key=>$value)
        {
			$data .= "<option value=".$value['speciality_id'].">".$value['speciality_title']."</option>";
        }
        return $data;
    }
}
