<?php 
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use backend\modules\article\models\Articles;
use common\models\User;
class ArticleController extends Controller
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
                $query = Articles::find()->all();
                foreach($query as $key=>$value)
                {
                    $value->file= "/backend/web/".$value->file;
                    $data[$key] = $value;
                }                
                return ['status' => true, 'message' => 'Articles', 'data' => $data];             
            }
        } 
        
    }
	
	public static function actionView()
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
                $query = Articles::find()->where(['articleId'=>$get['id']])->one();
				if(!empty($query))
				{
					$query->file = "/backend/web/".$query->file;
					$data = [$query];
				}             
				
                return ['status' => true, 'message' => 'Article view', 'data' => $data];             
            }
        } 
        
    }
	
	
	
    
    
}
?>