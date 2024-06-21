<?php

namespace backend\modules\common\controllers;

use Yii;
use backend\modules\common\models\Fooditems;
use backend\modules\common\models\FooditemsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use backend\modules\common\models\Fooditemdetails;
use backend\modules\common\models\Portions;
use yii\web\Response;
use yii\widgets\ActiveForm;
/**
 * FooditemsController implements the CRUD actions for Fooditems model.
 */
class FooditemsController extends Controller
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
     * Lists all Fooditems models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FooditemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fooditems model.
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
     * Creates a new Fooditems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Fooditems();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect(['addcal','id'=>$model->itemId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Fooditems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
		{
            $model->save();
            return $this->redirect(['addcal','id'=>$model->itemId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Fooditems model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Fooditems::findOne($id);
		$model->Status = 'In-active';
		$model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Fooditems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fooditems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fooditems::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	public function actionAddcal($id){
		$model = new Fooditemdetails();
		$model->portions = Portions::getPortions();
		$calories = Fooditemdetails::find()->where(['itemId'=>$id])->asArray()->all();
		if($calories != [])
		{
			for($i=0;$i<count($calories);$i++)
			{
				
				$model->calories[$i]['quantity'] = $calories[$i]['portionId'];
				$model->calories[$i]['cal'] = $calories[$i]['cal'];
				$model->calories[$i]['carbohydrates'] = $calories[$i]['carbohydrates'];
				$model->calories[$i]['proteins'] = $calories[$i]['proteins'];
				$model->calories[$i]['fat'] = $calories[$i]['fat'];
				$model->calories[$i]['fiber'] = $calories[$i]['fiber'];
			}
		}
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {			
         	Yii::$app->response->format = Response::FORMAT_JSON;
         	if (!$model->validate())
			{ 
               	return ActiveForm::validate($model);
    		}
    		else
			{
				Fooditemdetails::deleteAll(['itemId' => $_GET['id']]);
				for($k =0 ; $k < count($model->calories); $k++)
    			{			
					$portion = Portions::find()->where(['portionId'=>$model->calories[$k]['quantity']])->one();
					$newmodel = new Fooditemdetails();
					$newmodel->itemId = $id;
					$newmodel->portionId = $model->calories[$k]['quantity'];
					$newmodel->quantity = $portion->portionName;
					$newmodel->cal = $model->calories[$k]['cal'];
					$newmodel->carbohydrates = $model->calories[$k]['carbohydrates'];
					$newmodel->proteins = $model->calories[$k]['proteins'];
					$newmodel->fat = $model->calories[$k]['fat'];
					$newmodel->fiber = $model->calories[$k]['fiber'];
					$newmodel->save();						
    			}
			   
			}
			return $this->redirect(['index']);
		}
		
		return $this->render('addcal', [
            'model' => $model,
        ]);
	
	}
	
	public function actionCalories($id)
	{
		$model = new Fooditemdetails();
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
				
         	Yii::$app->response->format = Response::FORMAT_JSON;
         	if (!$model->validate())
			{ 
				
               	return ActiveForm::validate($model);
    		}
		}
		return $this->render('calories', [
            'model' => $model,
        ]);
		
	}

}
