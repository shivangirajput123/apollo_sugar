<?php

namespace backend\modules\users\controllers;

use Yii;
use backend\modules\users\models\Superadmin;
use backend\modules\users\models\SuperadminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use yii\web\UploadedFile;
use common\models\User;
/**
 * SuperadminController implements the CRUD actions for Superadmin model.
 */
class SuperadminController extends Controller
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
     * Lists all Superadmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SuperadminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Superadmin model.
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
     * Creates a new Superadmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Superadmin();
        $model->scenario = 'create';
        $model->cities = City::getCities();
		$model->locations = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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
		else{
			//print_r($model->errors);exit;
		}
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Superadmin model.
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
		$username = User::find()->where(['id'=>$model->userId])->one();
		$model->username = $username->username;
		//print_r($model->locationId);exit;
        if ($model->load(Yii::$app->request->post())) {
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
     * Deletes an existing Superadmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
		$model = Superadmin::findOne($id);
		$model->Status = 'In-active';
		$model->save();
		$user = User::find()->where(['id'=>$model->userId])->one();
		$user->status = 9;
		$user->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Superadmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Superadmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Superadmin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
