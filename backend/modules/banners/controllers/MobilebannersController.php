<?php

namespace backend\modules\banners\controllers;

use Yii;
use backend\modules\banners\models\Mobilebanners;
use backend\modules\banners\models\MobilebannersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

/**
 * MobilebannersController implements the CRUD actions for Mobilebanners model.
 */
class MobilebannersController extends Controller
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
     * Lists all Mobilebanners models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MobilebannersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mobilebanners model.
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
     * Creates a new Mobilebanners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mobilebanners();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->baner_image = UploadedFile::getInstance($model,'baner_image');
            if(!empty($model->baner_image))
            {
                $imageName = time().$model->baner_image->name;
                $model->baner_image->saveAs(Yii::getAlias('@frontend/web/images/home/mobilebanners/').$imageName );
                $model->baner_image = 'images/home/mobilebanners/'.$imageName;               
            }
            else{
                $model->baner_image = '';
            }
            $model->created_at = date('Y-m-d H:i;s');
            $model->updated_at = date('Y-m-d H:i;s');
            $model->save();
            return $this->redirect(['index']);
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mobilebanners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $banner = $model->baner_image;
        if ($model->load(Yii::$app->request->post()) ) {
            $model->baner_image = UploadedFile::getInstance($model,'baner_image');
            if(!empty($model->baner_image))
            {
                $imageName = time().$model->baner_image->name;
                $model->baner_image->saveAs(Yii::getAlias('@frontend/web/images/home/mobilebanners/').$imageName );
                $model->baner_image = 'images/home/mobilebanners/'.$imageName;
            }
            else{
                $model->baner_image = $banner;
            }
            
            $model->updated_at = date('Y-m-d H:i;s');
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mobilebanners model.
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
     * Finds the Mobilebanners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mobilebanners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mobilebanners::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
