<?php

namespace backend\modules\common\controllers;

use Yii;
use backend\modules\common\models\Durations;
use backend\modules\common\models\DurationsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
/**
 * DurationsController implements the CRUD actions for Durations model.
 */
class DurationsController extends Controller
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
     * Lists all Durations models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DurationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Durations model.
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
     * Creates a new Durations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Durations();

        if ($model->load(Yii::$app->request->post())&& $model->validate()) {
            
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Durations model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Durations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 'In-active';
		$model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Durations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Durations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Durations::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
