<?php

namespace backend\modules\packages\controllers;

use Yii;
use backend\modules\packages\models\Plans;
use backend\modules\packages\models\PlansSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
use common\models\User;
use backend\modules\packages\models\Packages;
use backend\modules\packages\models\Packageitems;
use backend\modules\packages\models\ItemDetails;
use backend\modules\packages\models\Planinclusions;
use yii\helpers\Json;
use backend\modules\users\models\Coach;
use backend\modules\users\models\Dietician;
use backend\modules\users\models\Doctors;
use backend\modules\packages\models\Plandoctors;
use backend\modules\packages\models\Planitems;
use backend\modules\packages\models\Plandetails;
use yii\web\Response;
use yii\widgets\ActiveForm;
use backend\modules\packages\models\Plansuggestions;
use frontend\models\Userplans;
/**
 * PlansController implements the CRUD actions for Plans model.
 */
class PlansController extends Controller
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
     * Lists all Plans models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlansSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Plans model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionView($id)
    {
		$model = $this->findModel($id);
		$createdBy = User::find()->where(['id'=>$model->createdBy])->one();
		$updatedBy = User::find()->where(['id'=>$model->updatedBy])->one();
		$packageitems = Planinclusions::find()->where(['planId'=>$model->planId])->asArray()->all();
		$data = array();
        foreach($packageitems as $key=>$value)
        {
            $data[$key] = $value['packageName'];
        }
		$model->inclusions = implode(',',$data);
		$model->createdBy = $createdBy->username;
		$model->updatedBy = $updatedBy->username;
		$model->createdDate = date('d-M-Y',strtotime($model->createdDate ));
		$model->updatedDate = date('d-M-Y',strtotime($model->updatedDate ));
		$duration = $model->duration;
		$consulations = $model->tenture/$duration;
		$plans = [];
		$inc = $duration;
		for($i=1;$i<= $consulations;$i++)
		{
			$plans[$i]['consultation'] = "Consultation - ".$i;
			
			if($i == 1)
			{
				$plans[$i]['date'] =  date('d-M-Y',strtotime($model->StartDate));
			}
			else
			{
				$plans[$i]['date'] =  date('d-M-Y',strtotime($model->StartDate.'+'.$inc.'days'));
				$inc = $inc + $duration;
			}
		}
		//print_r($plans);exit;
        return $this->render('view', [
            'model' => $model,
			'plans'=>$plans
        ]);
    }

    /**
     * Creates a new Plans model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Plans();
		$model->cities = City::getCities();
		$model->items = Packages::getItems();
		$model->doctors = Doctors::getDoctorsNew();
		$model->coaches = Coach::getCoach();
		$model->dieticans = Dietician::getDietician();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			
			$program = Plans::find()->where(['PlanName'=>$model->PlanName])->one();
			if(empty($program)){
			$model->save();
			
			foreach($model->inclusions as $key=>$value)
			{
				
				$discount = 0;
				$price = 0;
				$offerprice = 0;
				$inclusions = new Planinclusions();
				$inclusions->planId = $model->planId;
				$inclusions->packageId = $value;
				$package = Packages::find()->where(['packageId'=>$value])->one();
				$inclusions->packageName = $package->packageName;
				$packageitems = Packageitems::find()->where(['packageId'=>$value])->asArray()->all();
				foreach($packageitems as $k=>$v)
				{
					$item = ItemDetails::find()->where(['itemId'=>$v['itemId']])->one();
					$price =  $price + $item->rate;
					$offerprice =  $offerprice + $item->offerPrice;
					$discount =  $discount + $item->discount;
				}
				$inclusions->Price = $price;
				$inclusions->offerPrice = $offerprice;
				$inclusions->discount = $discount;
				$inclusions->save();
				
			}
			foreach($model->newitems as $xkey=>$xvalue)
			{
				$planitems = new Planitems();
				$planitems->planId = $model->planId;
				$planitems->itemId = $xvalue;
				$newitem = ItemDetails::find()->where(['itemId'=>$xvalue])->one();
				$planitems->ItemName = $newitem->itemName;
				$planitems->save();
			}
		/*	if(!empty($model->doctorId)){
				$doctorsmodel  = new Plandoctors();
				$doctorsmodel->planId = $model->planId;
				$doctorsmodel->roleId = 3;
				$doctor = User::find()->where(['id'=>$model->doctorId])->one();
				$doctorsmodel->userId = $model->doctorId;
				$doctorsmodel->name = $doctor->username;
				$doctorsmodel->save();
			}
			if(!empty($model->coachId)){
				$coachmodel  = new Plandoctors();
				$coachmodel->planId = $model->planId;
				$coachmodel->roleId = 4;
				$coach = User::find()->where(['id'=>$model->coachId])->one();
				$coachmodel->userId = $model->coachId;
				$coachmodel->name = $coach->username;
				$coachmodel->save();
			}
			if(!empty($model->dieticanId)){
				$dieticanmodel  = new Plandoctors();
				$dieticanmodel->planId = $model->planId;
				$dieticanmodel->roleId = 5;
				$dietican = User::find()->where(['id'=>$model->dieticanId])->one();
				$dieticanmodel->userId = $model->dieticanId;
				$dieticanmodel->name = $coach->username;
				$dieticanmodel->save();
			}*/
            
			return $this->redirect(['plansuggestion','id'=>$model->planId]);
		}
			else
			{
				$model->addError('PlanName','Plan Name Already Exist');
				return $this->render('create', [
					'model' => $model,
				]);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
	
	public function actionPlandetails()
    {
        $model = $this->findModel($_GET['id']);
		
		$duration = $model->duration;		
		$createdBy = User::find()->where(['id'=>$model->createdBy])->one();
		$updatedBy = User::find()->where(['id'=>$model->updatedBy])->one();
		
		//print_r($model->inclusions);exit;
		$packageitems = Planinclusions::find()->where(['planId'=>$model->planId])->asArray()->all();
		$data = array();
        foreach($packageitems as $key=>$value)
        {
            $data[$key] = $value['packageName'];
        }
		$items = Planitems::find()->where(['planId'=>$model->planId])->asArray()->all();
		$itemsdata = array();
        foreach($items as $k=>$v)
        {
            $itemsdata[$k] = $v['ItemName'];
        }
		//print_r($itemsdata);exit;
		$model->newitems = implode(',',$itemsdata);
		//print_r($model->newitems);exit;
		$model->inclusions = implode(',',$data);
		$inclusions = Planinclusions::getInclusions($_GET['id']);
		//print_r($inclusions);exit;
		$consulations = $model->tenture/$duration;
		$plans = [];
		$inc = $duration;
		$newmodel = new Plandetails();
		$details = Plandetails::find()->where(['planId'=>$_GET['id']])->all();		
		$count = 0;
		if($details != [])
		{
			foreach($details as $x=>$v)
			{
				$newmodel->details[$x]['day'] = $v['day'];
				$newmodel->details[$x]['endday'] = $v['endday'];
				$newmodel->details[$x]['text'] = explode(',',$v['text']);
			}
			$count = count($details);
		}
		else
		{
			$planinc = Planitems::find()->where(['planId'=>$_GET['id']])->orderBy("planItemId DESC")->asArray()->all();
			foreach($planinc as $ix=>$iv)
			{
				
				$newmodel->details[$ix]['day'] = '';
				$newmodel->details[$ix]['endday'] = '';
				$newmodel->details[$ix]['text'] = $iv['itemId'];
			}
			$count = count($inclusions);
		}
        if ($newmodel->load(Yii::$app->request->post()) && $newmodel->validate()) 
		{
				Yii::$app->response->format = Response::FORMAT_JSON;
				if (!$newmodel->validate()) { 
					return ActiveForm::validate($newmodel);
				}
				else
				{
				    Plandetails::deleteAll(['planId' => $_GET['id']]);
					//print_r();exit;
					for($k =0 ; $k < count($newmodel->details); $k++)
					{
			
						$plandetails = new Plandetails();
						$plandetails->planId = $_GET['id'];
						$plandetails->day = $newmodel->details[$k]['day'];
				        $plandetails->endday = $newmodel->details[$k]['endday'];
						$plandetails->text = implode(',',$newmodel->details[$k]['text']);
						$plandetails->createdBy = Yii::$app->user->identity->id;
						$plandetails->updatedBy = Yii::$app->user->identity->id;
						$plandetails->save();
					}
				}
				Yii::$app->session->setFlash('success', "Sugar Program Updated successfully.");
				 return $this->redirect(['index']);
        }
		//print_r($plans);exit;
        return $this->render('plandetails', [
            'model' => $model,
			'newmodel'=>$newmodel,
			'plans'=>$plans,
			'inclusions'=>$inclusions,
			'count'=>$count
        ]);
    }


    /**
     * Updates an existing Plans model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->inclusions = Planinclusions::getInclusionsById($id);
		$model->items = Packages::getItems();
		$model->newitems = Packages::getItemsNewBYId($id);
		$model->itemsnew = Packages::getItemsNew($id);
		//print_r($model->newitems);exit;
		$model->doctors = Doctors::getDoctorsNew();
		$model->coaches = Coach::getCoach();
		$model->dieticans = Dietician::getDietician();
		//$model->programapplicable = explode(',',$model->programapplicable);
        if ($model->load(Yii::$app->request->post())) {
			//$model->programapplicable = implode(',',$model->programapplicable);
            $model->save();
			Planinclusions::deleteAll(['planId' => $model->planId]);
			foreach($model->inclusions as $key=>$value)
			{
				
				$discount = 0;
				$price = 0;
				$offerprice = 0;
				$inclusions = new Planinclusions();
				$inclusions->planId = $model->planId;
				$inclusions->packageId = $value;
				$package = Packages::find()->where(['packageId'=>$value])->one();
				$inclusions->packageName = $package->packageName;
				$packageitems = Packageitems::find()->where(['packageId'=>$value])->asArray()->all();
				foreach($packageitems as $k=>$v)
				{
					$item = ItemDetails::find()->where(['itemId'=>$v['itemId']])->one();
					$price =  $price + $item->rate;
					$offerprice =  $offerprice + $item->offerPrice;
					$discount =  $discount + $item->discount;
				}
				$inclusions->Price = $price;
				$inclusions->offerPrice = $offerprice;
				$inclusions->discount = $discount;
				$inclusions->save();
			
			}
			
			Planitems::deleteAll(['planId' => $model->planId]);
			foreach($model->newitems as $xkey=>$xvalue)
			{
				//print_r($model->newitems);exit;
				$planitems = new Planitems();
				$planitems->planId = $model->planId;
				$planitems->itemId = $xvalue;
				$newitem = ItemDetails::find()->where(['itemId'=>$xvalue])->one();
				$planitems->ItemName = $newitem->itemName;
				$planitems->save();
			}
			
			Plandoctors::deleteAll(['planId' => $model->planId]);
		/*	if(!empty($model->doctorId)){
				$doctorsmodel  = new Plandoctors();
				$doctorsmodel->planId = $model->planId;
				$doctorsmodel->roleId = 3;
				$doctor = User::find()->where(['id'=>$model->doctorId])->one();
				$doctorsmodel->userId = $model->doctorId;
				$doctorsmodel->name = $doctor->username;
				$doctorsmodel->save();
			}
			if(!empty($model->coachId)){
				$coachmodel  = new Plandoctors();
				$coachmodel->planId = $model->planId;
				$coachmodel->roleId = 4;
				$coach = User::find()->where(['id'=>$model->coachId])->one();
				$coachmodel->userId = $model->coachId;
				$coachmodel->name = $coach->username;
				$coachmodel->save();
			}
			if(!empty($model->dieticanId)){
				$dieticanmodel  = new Plandoctors();
				$dieticanmodel->planId = $model->planId;
				$dieticanmodel->roleId = 5;
				$dietican = User::find()->where(['id'=>$model->dieticanId])->one();
				$dieticanmodel->userId = $model->dieticanId;
				$dieticanmodel->name = $coach->username;
				$dieticanmodel->save();
			}*/
			
            return $this->redirect(['plansuggestion','id'=>$model->planId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Plans model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Plans::findOne($id);		
		$model->Status = 'In-active';
		$plans = Userplans::find()->where(['planId'=>$id])->one();
		if(empty($plans))
		{
			$model->save();
			return $this->redirect(['index']);
		}
		else
		{
			Yii::$app->session->setFlash('error', "Sugar Program Already Subscribed.");
		    return $this->redirect(['index']);
		}
    }

    /**
     * Finds the Plans model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plans the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plans::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	public function actionGetprices(){
		$model = $_GET['options'];
		$data = array();
		$price = 0;
		$offerprice = 0;
		$discount = 0;
        foreach($model as $key=>$value)
        {
			$packageitems = Packageitems::find()->where(['packageId'=>$value])->asArray()->all();
			foreach($packageitems as $k=>$v)
			{
				$item = ItemDetails::find()->where(['itemId'=>$v['itemId']])->one();
				$price =  $price + $item->rate;
				$offerprice =  $offerprice + $item->offerPrice;
				$discount =  $item->discount;
			}
			 
		}
		$data['price'] = $price ;
		$data['offerprice'] = $offerprice ;
		$data['discount'] = $discount ;
		return json_encode($data);
	}
	
	public function actionGetnewprices(){
		$model = $_GET['options'];
		$data = array();
		$price = 0;
		$offerprice = 0;
		$discount = 0;
        foreach($model as $key=>$value)
        {
				$item = ItemDetails::find()->where(['itemId'=>$value])->one();
				$price =  $price + $item->rate;
				$offerprice =  $offerprice + $item->offerPrice;
				$discount =  $item->discount;			 
		}
	//	print_r($discount);exit;
		$data['price'] = $price ;
		$data['offerprice'] = $offerprice ;
		$data['discount'] = $discount ;
		return json_encode($data);
	}
	public function actionGetitems(){
		$model = $_GET['options'];		
		$data = array();
		$out = Packages::getPackageItmes($model);
		//print_r($out);exit;
		foreach($out as $key=>$value)
		{
			echo '<option value='.$value['id'].' selected>'.$value['name'].'</option>';
		}
	}
	public function actionItems()
    {
        $out = [];
       
        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'][0];
			
            if ($parents != []) 
			{
				
				$cat_id = $parents;
				$out = Packages::getPackageItmes($cat_id);
				echo Json::encode(['output'=>$out, 'selected'=>'']);
				return;
            }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
            }
			public function actionPlansuggestion()
			{
				$model = Plansuggestions::find()->where(['planId'=>$_GET['id']])->one();
				if(empty($model))
				{
					$model = new Plansuggestions();
				}
				if ($model->load(Yii::$app->request->post())) {
					if ($model->validate()) {
						$model->planId = $_GET['id'];
						$model->save();
						return $this->redirect(['plandetails','id'=>$model->planId]);
					}
				}

				return $this->render('plansuggestion', [
					'model' => $model,
				]);
			}

}
