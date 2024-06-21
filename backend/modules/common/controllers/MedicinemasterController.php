<?php

namespace backend\modules\common\controllers;

use Yii;
use backend\modules\common\models\Medicinemaster;
use backend\modules\common\models\MedicinemasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
/**
 * MedicinemasterController implements the CRUD actions for Medicinemaster model.
 */
class MedicinemasterController extends Controller
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
     * Lists all Medicinemaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MedicinemasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Medicinemaster model.
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
     * Creates a new Medicinemaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Medicinemaster();

        if ($model->load(Yii::$app->request->post())&& $model->validate()) {
            
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Medicinemaster model.
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
     * Deletes an existing Medicinemaster model.
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
     * Finds the Medicinemaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Medicinemaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Medicinemaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
