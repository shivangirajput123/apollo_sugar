<?php

namespace backend\modules\packages\controllers;

use Yii;
use backend\modules\packages\models\Packages;
use backend\modules\packages\models\PackagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
use backend\modules\packages\models\ItemDetails;
use backend\modules\packages\models\Packageitems;
/**
 * PackagesController implements the CRUD actions for Packages model.
 */
class PackagesController extends Controller
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
     * Lists all Packages models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PackagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Packages model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
		$createdBy = User::find()->where(['id'=>$model->createdBy])->one();
		$updatedBy = User::find()->where(['id'=>$model->updatedBy])->one();
		$packageitems = Packageitems::find()->where(['packageId'=>$model->packageId])->asArray()->all();
		$data = array();
        foreach($packageitems as $key=>$value)
        {
            $data[$key] = $value['itemName'];
        }
		$model->inclusions = implode(',',$data);
		$model->createdBy = $createdBy->username;
		$model->updatedBy = $updatedBy->username;
		$model->createdDate = date('d-M-Y',strtotime($model->createdDate ));
		$model->updatedDate = date('d-M-Y',strtotime($model->updatedDate ));
        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new Packages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Packages();

        $model->cities = City::getCities();
		$model->items = ItemDetails::getItems();
		$model->locations = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			
            $model->save();
			foreach($model->inclusions as $key=>$value)
			{
				//print_r($value);exit;
				$inclusions = new Packageitems();
				$inclusions->packageId = $model->packageId;
				$inclusions->PackageName = $model->packageName;
				$item = ItemDetails::find()->where(['itemId'=>$value])->one();
				$inclusions->itemId = $value;
				$inclusions->ItemCode = $item->itemCode;
				$inclusions->itemName = $item->itemName;
				$inclusions->price = $item->rate;
				$inclusions->save();
				
			}
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Packages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->cities = City::getCities();
		$model->inclusions = Packageitems::getInclusionsById($id);
		$model->items = ItemDetails::getItems();
		$model->locations = Location::getLocationsByID($model->cityId);
        if ($model->load(Yii::$app->request->post())) {
			Packageitems::deleteAll(['packageId' => $model->packageId]);
			foreach($model->inclusions as $key=>$value)
			{
				//print_r($value);exit;
				$inclusions = new Packageitems();
				$inclusions->packageId = $model->packageId;
				$inclusions->PackageName = $model->packageName;
				$item = ItemDetails::find()->where(['itemId'=>$value])->one();
				$inclusions->itemId = $value;
				$inclusions->ItemCode = $item->itemCode;
				$inclusions->itemName = $item->itemName;
				$inclusions->price = $item->rate;
				$inclusions->save();
				
			}
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Packages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Packages::findOne($id);
		
		$model->Status = 'In-active';
		if(!($model->save()))
		{
				print_r($model->errors);exit;
		}

        return $this->redirect(['index']);
    }

    /**
     * Finds the Packages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Packages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Packages::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
