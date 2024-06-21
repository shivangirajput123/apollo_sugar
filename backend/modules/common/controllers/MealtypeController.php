<?php

namespace backend\modules\common\controllers;

use Yii;
use backend\modules\common\models\Mealtype;
use backend\modules\common\models\MealtypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
/**
 * MealtypeController implements the CRUD actions for Mealtype model.
 */
class MealtypeController extends Controller
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
     * Lists all Mealtype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MealtypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mealtype model.
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
     * Creates a new Mealtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mealtype();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mealtype model.
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
     * Deletes an existing Mealtype model.
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
     * Finds the Mealtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mealtype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mealtype::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
