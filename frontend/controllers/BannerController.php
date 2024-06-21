<?php 
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use backend\modules\banners\models\Mobilebanners;
use common\models\User;
class BannerController extends Controller
{
    public static function actionIndex()
    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $data = array();        
        $get = Yii::$app->request->get();
		$Authorization = Yii::$app->request->headers->get('Authorization');		
        if (empty($Authorization))
        {
            return ['status' => false, 'message' => 'Please Add Authorization Token '];
        }
        else
        {
            $user = User::find()
            ->where(['access_token' => str_replace('Bearer ','',$Authorization)])
            ->andWhere(['=', 'status', User::STATUS_ACTIVE])
            ->orderBy(['id'=> SORT_DESC])
            ->one();
            if(empty($user))
            {
                return ['status' => false, 'message' => 'Invalid Access token'];
            }
            else
            {
                $query = Mobilebanners::find()->where(['type'=>NULL,'status'=>0])->all();
                foreach($query as $key=>$value)
                {
                    $value->baner_image= "https://devapp.apollohl.in:8443/ApolloSugar/frontend/web/".$value->baner_image;
                    $data[$key] = $value;
                }                
                return ['status' => true, 'message' => 'Banners', 'data' => $data];             
            }
        } 
        
    }
    
    public static function actionCategoryBanners()
    {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
        $result = array();
        $get = Yii::$app->request->get();
        $data1 = array();
        $data2 = array();
        $user = User::find()
                ->where(['access_token' => $get['access_token']])
                ->andWhere(['=', 'status', User::STATUS_ACTIVE])
                ->one();
        
        if(!empty($user))
        {
            $query = Mobilebanners::find()->where(['type'=>'Category'])->all();
            foreach($query as $key=>$value)
            {
                $value['baner_image']= "https://www.apollodiagnostics.in/frontend/web/".$value['baner_image'];
                if($value['baner_name'] == 'COVID 19 RT PCR HOME COLLECTION')
                {
                    $item = PackageItems::find()->where(['item_id'=>$value['itemcode'],'state_id'=>$get['state_id'],'city_id'=>$get['city_id']])->one();
                    $value['rate'] = $item['rate'];
                    $value['item_name']= $item['item_name'];
                    $data1[] = $value;
                }
                else
                {
                    $data2[] = $value;
                }
            }
            
            return ['status' => true, 'message' => 'Success', 'banner1'=>$data1,'banner2'=>$data2];
        }
        else
        {
            return ['status' => false, 'message' => 'UnAuthorised User'];
        }
        
    }
}
?>