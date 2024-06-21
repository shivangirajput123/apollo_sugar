<?php

namespace backend\modules\plans\controllers;

use Yii;
use backend\modules\plans\models\Excerciseplans;
use backend\modules\plans\models\Excerciseplandetails;
use backend\modules\plans\models\ExcerciseplansSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Userprofile;
use backend\modules\common\models\Excercise;
use yii\web\Response;
use yii\widgets\ActiveForm;
/**
 * ExcerciseplansController implements the CRUD actions for Excerciseplans model.
 */
class ExcerciseplansController extends Controller
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
     * Lists all Excerciseplans models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExcerciseplansSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Excerciseplans model.
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
     * Creates a new Excerciseplans model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Excerciseplans();
		$model->users = Userprofile::getProfiles();
		$model->excerciselist = Excercise::excerciselist();
		$d = strtotime("today");
		$currentstart_week = strtotime("last sunday midnight",$d);
		$currentend_week = strtotime("next saturday",$d);
		$start = date("Y-m-d",$currentstart_week); 
		$end = date("Y-m-d",$currentend_week);
		$times = Excerciseplans::find()->where(['userId'=>$id,'createdDate'=>$start])->all();
		if(!empty($times))
		{
			for($x=0;$x<count($times);$x++)
			{				
				$model->times[$x]['explanId'] = $times[$x]['explanId'];
				$model->times[$x]['time'] = $times[$x]['time'];
				$timedetails = Excerciseplandetails::find()->where(['explanId'=>$times[$x]['explanId']])->all();
				if(!empty($timedetails))
				{
					for($y=0;$y<count($timedetails);$y++)
					{
						$model->times[$x]['excercises'][$y]['explandetId'] = $timedetails[$y]['explandetId'];
						$model->times[$x]['excercises'][$y]['excercise'] = $timedetails[$y]['excerciseId'];
						$model->times[$x]['excercises'][$y]['distance'] = $timedetails[$y]['distance'];						
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
				//print_r($model->times);exit;
				for($k =0 ; $k < count($model->times); $k++)
    			{
					if(empty($model->times[$k]['explanId']))
					{	
						$newmodel = new Excerciseplans();
						$user = Userprofile::find()->where(['userId'=>$id])->one();
						$newmodel->userId = $id;
						$newmodel->username = $user->firstName;
						$newmodel->time = $model->times[$k]['time'];
						$newmodel->createdDate = date('Y-m-d H:i;s');
						$newmodel->updatedDate = date('Y-m-d H:i;s');
						$newmodel->createdBy = Yii::$app->user->id;
						$newmodel->updatedBy = Yii::$app->user->id;
						$newmodel->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
						if($newmodel->save())
						{
								Excerciseplandetails::deleteAll(['explanId' => $newmodel->explanId]);
								for($i=0;$i<count($model->times[$k]['excercises']);$i++)
								{
									$Excerciseplandetails = new Excerciseplandetails();
									$Excerciseplandetails->explanId = $newmodel->explanId;
									$Excerciseplandetails->excerciseId = $model->times[$k]['excercises'][$i]['excercise'];
									$excercise = Excercise::find()->where(['ExcerciseId'=>$model->times[$k]['excercises'][$i]['excercise']])->one();
									$Excerciseplandetails->title = $excercise->title;
									$Excerciseplandetails->distance = $model->times[$k]['excercises'][$i]['distance'];
									$Excerciseplandetails->save();
								}
						}
					}
					else
					{
						$time = Excerciseplans::find()->where(['explanId'=>$model->times[$k]['explanId']])->one();
						$time->time = $model->times[$k]['time'];
						$time->updatedBy = Yii::$app->user->identity->id;
						$time->updatedDate =  date('Y-m-d H:i:s');
						$time->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
						$time->save();
						Excerciseplandetails::deleteAll(['explanId' => $time->explanId]);
						for($z=0;$z<count($model->times[$k]['excercises']);$z++)
						{
							$Excerciseplandetails = new Excerciseplandetails();
							$Excerciseplandetails->explanId = $time->explanId;
							$Excerciseplandetails->excerciseId = $model->times[$k]['excercises'][$z]['excercise'];
							$excercise = Excercise::find()->where(['ExcerciseId'=>$model->times[$k]['excercises'][$z]['excercise']])->one();
							$Excerciseplandetails->title = $excercise->title;
							$Excerciseplandetails->distance = $model->times[$k]['excercises'][$z]['distance'];
							$Excerciseplandetails->save();
						}
					}
				}
				
			}
			//return $this->redirect(['index']);
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Excerciseplans model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->explanId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Excerciseplans model.
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
     * Finds the Excerciseplans model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Excerciseplans the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Excerciseplans::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
