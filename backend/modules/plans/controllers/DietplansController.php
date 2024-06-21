<?php

namespace backend\modules\plans\controllers;

use Yii;
use backend\modules\plans\models\Dietplans;
use backend\modules\plans\models\DietplansSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\Mealtype;
use backend\modules\common\models\Fooditems;
use yii\web\Response;
use yii\widgets\ActiveForm;
use backend\modules\plans\models\Dietplandetails;
/**
 * DietplansController implements the CRUD actions for Dietplans model.
 */
class DietplansController extends Controller
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
     * Lists all Dietplans models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DietplansSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dietplans model.
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
     * Creates a new Dietplans model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Dietplans();
		$model->mealtypes = Mealtype::getTypes();
		$model->fooditems = Fooditems::getItems();
		$d = strtotime("today");
		$currentstart_week = strtotime("last sunday midnight",$d);
		$currentend_week = strtotime("next saturday",$d);
		$start = date("Y-m-d",$currentstart_week); 
		$end = date("Y-m-d",$currentend_week);
		$times = Dietplans::find()->where(['userId'=>$id,'createdDate'=>$start])->all();
		if(!empty($times))
		{
			for($x=0;$x<count($times);$x++)
			{				
				$model->times[$x]['planId'] = $times[$x]['planId'];
				$model->times[$x]['time'] = $times[$x]['time'];
				$model->times[$x]['mealtype'] = $times[$x]['mealtypeId'];
				$timedetails = Dietplandetails::find()->where(['planId'=>$times[$x]['planId']])->all();
				if(!empty($timedetails))
				{
					for($y=0;$y<count($timedetails);$y++)
					{
						$model->times[$x]['items'][$y]['dietplanId'] = $timedetails[$y]['dietplanId'];
						$model->times[$x]['items'][$y]['item'] = $timedetails[$y]['itemId'];
						$model->times[$x]['items'][$y]['quantity'] = $timedetails[$y]['quantity'];		
						$model->times[$x]['items'][$y]['calories'] = $timedetails[$y]['calories'];								
					}					 
				}
			}
			
		}
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {			
         	Yii::$app->response->format = Response::FORMAT_JSON;
         	if (!$model->validate())
			{ 		
				//print_r($model->errors);exit;
               	return ActiveForm::validate($model);
    		}
    		else
			{
				for($k =0 ; $k < count($model->times); $k++)
    			{
					if(empty($model->times[$k]['planId']))
					{	
						$newmodel = new Dietplans();
						$newmodel->userId = $id;
						$mealtype = Mealtype::find()->where(['id'=>$model->times[$k]['mealtype']])->one();	
						$newmodel->mealtypeId = $model->times[$k]['mealtype'];						
						$newmodel->mealtype = $mealtype->type;
						$newmodel->time = $model->times[$k]['time'];
						$newmodel->createdDate = date('Y-m-d H:i;s');
						$newmodel->updatedDate = date('Y-m-d H:i;s');
						$newmodel->createdBy = Yii::$app->user->id;
						$newmodel->updatedBy = Yii::$app->user->id;
						$newmodel->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
						if($newmodel->save())
						{
								Dietplandetails::deleteAll(['planId' => $newmodel->planId]);
								for($i=0;$i<count($model->times[$k]['items']);$i++)
								{
									$Dietplandetails = new Dietplandetails();
									$Dietplandetails->planId = $newmodel->planId;
									$Dietplandetails->itemId = $model->times[$k]['items'][$i]['item'];
									$item = Fooditems::find()->where(['itemId'=>$model->times[$k]['items'][$i]['item']])->one();
									$Dietplandetails->itemName = $item->itemName;
									$Dietplandetails->quantity = $model->times[$k]['items'][$i]['quantity'];
									$Dietplandetails->calories = $model->times[$k]['items'][$i]['calories'];
									$Dietplandetails->save();
								}
						}
					}
					else
					{
						$time = Dietplans::find()->where(['planId'=>$model->times[$k]['planId']])->one();
						$mealtype = Mealtype::find()->where(['id'=>$model->times[$k]['mealtype']])->one();	
						$time->mealtypeId = $model->times[$k]['mealtype'];						
						$time->mealtype = $mealtype->type;
						$time->time = $model->times[$k]['time'];
						$time->updatedBy = Yii::$app->user->identity->id;
						$time->updatedDate =  date('Y-m-d H:i:s');
						$time->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
						$time->save();
						Dietplandetails::deleteAll(['planId' => $time->planId]);
						for($z=0;$z<count($model->times[$k]['items']);$z++)
						{
							$Dietplandetails = new Dietplandetails();
							$Dietplandetails->planId = $time->planId;
							$Dietplandetails->itemId = $model->times[$k]['items'][$z]['item'];
							$item = Fooditems::find()->where(['itemId'=>$model->times[$k]['items'][$z]['item']])->one();
							$Dietplandetails->itemName = $item->itemName;
							$Dietplandetails->quantity = $model->times[$k]['items'][$z]['quantity'];
							$Dietplandetails->calories = $model->times[$k]['items'][$z]['calories'];
							$Dietplandetails->save();
						}
					}
				}
				
			}
		}

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Dietplans model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->planId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Dietplans model.
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
     * Finds the Dietplans model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dietplans the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dietplans::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
