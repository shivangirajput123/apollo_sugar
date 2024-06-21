<?php

namespace backend\modules\common\models;

use Yii;
use backend\modules\common\models\City;
use backend\modules\common\models\Location;
/**
 * This is the model class for table "specialties".
 *
 * @property int $speciality_id
 * @property string|null $speciality_name
 * @property string|null $speciality_title
 * @property string|null $seo_url
 * @property string|null $metaTitle
 * @property string|null $metaDescription
 * @property string|null $metaKeyword
 * @property string|null $description
 * @property int|null $cityId
 * @property string|null $cityName
 * @property int|null $locationId
 * @property string|null $locatonName
 * @property string $Status
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $createdDate
 * @property string $updatedDate
 * @property string $ipAddress
 */
class Specialties extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'specialties';
    }

    /**
     * {@inheritdoc}
     */
	public $cities;
	public $locations;
    public function rules()
    {
        return [
            [['Status', 'speciality_name', 'speciality_title'], 'required'],
            [['speciality_id', 'cityId', 'locationId', 'createdBy', 'updatedBy'], 'integer'],
            [['metaDescription', 'description', 'Status'], 'string'],
            [['createdDate', 'updatedDate','cities','locations','locationName'], 'safe'],
            [['speciality_name', 'speciality_title', 'seo_url', 'metaTitle', 'metaKeyword'], 'string', 'max' => 250],
            [['cityName', 'locationName'], 'string', 'max' => 200],
			[['speciality_name'],'unique'],
            [['ipAddress'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'speciality_id' => 'Speciality ID',
            'speciality_name' => 'Speciality Name',
            'speciality_title' => 'Speciality Title',
            'seo_url' => 'Seo Url',
            'metaTitle' => 'Meta Title',
            'metaDescription' => 'Meta Description',
            'metaKeyword' => 'Meta Keyword',
            'description' => 'Description',
            'cityId' => 'City',
            'cityName' => 'City Name',
            'locationId' => 'Location',
            'locatonName' => 'Locaton Name',
            'Status' => 'Status',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
	public function beforeSave($insert) {
		//echo 'hi';exit;
   /*     $city = City::find()->where(['id'=>$this->cityId])->one();
		$location = Location::find()->where(['id'=>$this->locationId])->one();
        $this->cityName = $city->title;
		$this->locationName = $location->title;*/
        if ($this->isNewRecord) 
        {
			$this->createdDate = date('Y-m-d H:i;s');
            $this->updatedDate = date('Y-m-d H:i;s');
            $this->createdBy = Yii::$app->user->id;
            $this->updatedBy = Yii::$app->user->id;
			$this->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);       
        } 
        else 
        {
            $this->updatedDate = date('Y-m-d H:i;s');            
            $this->updatedBy = Yii::$app->user->id;
			$this->ipAddress = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
        }      
        return parent::beforeSave($insert);
    }
	public static function getSpecialties()
    {
        $model = Specialties::find()->orderBy('speciality_id DESC')->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['speciality_id']] = $value['speciality_name'];
        }
        return $data;
    }
}
