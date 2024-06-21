<?php

namespace backend\modules\users\controllers;

use Yii;
use backend\modules\users\models\Doctors;
use backend\modules\users\models\DoctorsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use yii\web\UploadedFile;
use common\models\User;
use backend\modules\users\models\Slots;
use yii\web\Response;
use yii\widgets\ActiveForm;
use backend\modules\common\models\Specialties;
use backend\modules\users\models\Doctorspecialites;
use backend\modules\clinics\models\Clinics;
/**
 * DoctorsController implements the CRUD actions for Doctors model.
 */
class DoctorsController extends Controller
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
     * Lists all Doctors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DoctorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionDoctorroster()
	{
		$model = Doctors::find();
		return $this->render('doctorroster',[
			'model'=>$model
		]);
		
	}
    /**
     * Displays a single Doctors model.
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
     * Creates a new Doctors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Doctors();
		$model->scenario = 'create';
        $model->cities = City::getCities();
		$model->locations = [];
		$model->clinics = [];
		if(Yii::$app->user->identity->roleName == 'Clinic')
		{
			$clinic = Clinics::find()->where(['userId'=>Yii::$app->user->id])->one();
			$model->cityId =$clinic->cityId; 
			$model->locations = Location::getLocationsByID($model->cityId);
			$model->locationId =$clinic->stateId;
		    $model->clinics = Clinics::getClinicsByID($model->locationId);	
            $model->clinicId =$clinic->clinicId;			
			
		}	
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->doctorName = $model->username;
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
			
            if(!$model->save())
			{
				print_r($model->errors);exit;	
			}
			
             return $this->redirect(['slots','id'=>$model->doctorId,'date'=>date('Y-m-d')]);
       
			
        }
			
        

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Doctors model.
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
		$model->clinics = Clinics::getClinicsByID($model->locationId);;
		$specialities = Doctorspecialites::find()->where(['doctorId'=>$id])->all();
		$speciality = [];
		if(!empty($specialities))
		{
			foreach($specialities as $k=>$v)
			{
				$speciality[$k] = $v['specialityId'];
			}
		}
		$model->speciality = $speciality;
		$model->specialities = Specialties::getSpecialties($model->locationId);
		//print_r($model->specialities);exit;
		$profileimage = $model->profileImage;
		$username = User::find()->where(['id'=>$model->userId])->one();
		$model->username = $username->username;
        if ($model->load(Yii::$app->request->post())) {
			/*Doctorspecialites::deleteAll(['doctorId' => $id]);
			foreach($model->speciality as $key=>$value)
			{
				$newmodel = new Doctorspecialites();
				$newmodel->doctorId = $id;
				$newmodel->doctorName = $model->doctorName;
				$newmodel->specialityId = $value;
				$specialityName = Specialties::find()->where(['speciality_id'=>$value])->one();
				$newmodel->specialityName = $specialityName->speciality_name;
				$newmodel->save();
			}*/
			$model->doctorName = $model->username;
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
            return $this->redirect(['slots','id'=>$model->doctorId,'date'=>date('Y-m-d')]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Doctors model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Doctors::findOne($id);
		$model->Status = 'In-active';
		$model->save();
		$user = User::find()->where(['id'=>$model->userId])->one();
		$user->status = 9;
		$user->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Doctors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doctors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Doctors::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	public function actionSlots($id){
		$model = new Slots();
		$doctor = Doctors::find()->where(['doctorId'=>$_GET['id']])->one();
		$model->timings = $doctor->timings;
		$model->duration = $doctor->duration;
		$slots = Slots::find()->where(['doctorid'=>$id,'slotDate'=>$_GET['date']])->asArray()->all();
		if($slots != [])
		{
			for($i=0;$i<count($slots);$i++)
			{
				$model->slots[$i]['slotId'] = $slots[$i]['slotId'];
				$model->slots[$i]['slotTime'] = $slots[$i]['slotTime'];
			}
		}
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
         	Yii::$app->response->format = Response::FORMAT_JSON;
         	if (!$model->validate()) { 
               	return ActiveForm::validate($model);
    		}
    		else
			{       
					$begin = strtotime($model->slotDate);
                    $end   = strtotime($model->endDate);
				
					for($i = $begin; $i <= $end; $i = $i + 86400){
					{
						$date = date('Y-m-d',$i);
						Slots::deleteAll(['doctorId'=>$id,'slotDate'=>$date]);
						//print_r($model->slots);exit;
						for($k =0 ; $k < count($model->slots); $k++)
						{
							
								$newmodel = new Slots();
								$newmodel->doctorId = $id;
								$newmodel->slotDate = $date;
								$newmodel->slotTime = $model->slots[$k]['slotTime'];
								$newmodel->createdBy = Yii::$app->user->identity->id;
								$newmodel->createdDate =  date('Y-m-d H:i:s');
								$newmodel->updatedBy = Yii::$app->user->identity->id;
								$newmodel->updatedDate =  date('Y-m-d H:i:s');
								$newmodel->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
								$newmodel->save();
								//print_r($newmodel->errors);exit;						
						}
					}
									
			}
			$doctor->timings = $model->timings;
			$doctor->duration = $model->duration;
			$doctor->save();
			return $this->redirect(['index']);
		  }
			
		}

		return $this->render('slots', [
            'model' => $model,
        ]);
	
	}
	
	public function actionGetslots()
	{
		$data = '<table class="multiple-input-list table table-condensed table-renderer"><tbody>';
		
		$newtimings = explode(', ',$_GET['timings']);
		$times = [];
		$i = 0;
		foreach($newtimings as $key=>$value)
		{
			$times= explode('to',$value);	
			$starttime = date("H:i", strtotime($times[0]));
			$endtime = date("H:i", strtotime($times[1]));
			
			
			$times[$i] = $starttime;
			while($times[$i] <= $endtime)
			{
				$convert = date('h:iA', strtotime($times[$i]));
				
				$data .= '<tr class="multiple-input-list__item"><td class="list-cell__slotTime"><input type="hidden" id="slots-slots-0-slotid" name="Slots[slots][0][slotId]" value=""><div class="field-slots-slots-0-slottime form-group"><input type="text" id="slots-slots-0-slottime" class="form-control" name="Slots[slots]['.$i.'][slotTime]" value='.$convert.' style="width: 100%;">
							<div class="help-block help-block-error"></div></div></td>
							<td class="list-cell__button"></td></tr>';

				$i++;
				$times[$i] = date("H:i", strtotime('+'.$_GET['duration'].'minutes', strtotime($times[$i-1])));
			}
		}
		$data .= '</tbody></table>';
		echo $data;exit;
	}
	
	public function actionDoctors()
    {
        $model = Doctorspecialites::find()->where(['specialityId' => $_GET['speciality']])->orderBy('docspecId DESC')->distinct('doctorId')->asArray()->all();
        $data ='<option>select option</option>';
        foreach($model as $key=>$value)
        {
			$data .= "<option value=".$value['doctorId'].">".$value['doctorName']."</option>";
        }
        return $data;
    }
}
