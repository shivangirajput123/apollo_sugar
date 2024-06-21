<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "userprofile".
 *
 * @property int|null $profileId
 * @property string|null $firstName
 * @property int|null $userId
 * @property string|null $lastName
 * @property string|null $gender
 * @property string|null $profilePic
 * @property string $DOB
 * @property float|null $weight
 * @property float|null $height
 * @property string|null $age
 * @property string|null $familyhistory
 * @property string|null $glucosescore
 * @property string|null $diabeticcondition
 * @property string|null $createdDate
 * @property string|null $updatedDate
 */
class Userprofile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userprofile';
    }
	public $Mobile;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profileId', 'userId'], 'integer'],
           // [['DOB'], 'required'],
            [['DOB', 'createdDate', 'updatedDate','access_token','period','manageDiabetes','typicalDay','expLowSugar','HbA1c','Status','Pregnancystatus','existingCondtions','Mobile'], 'safe'],
            [['weight', 'height'], 'number'],
            [['firstName', 'lastName', 'gender', 'profilePic', 'familyhistory', 'glucosescore', 'diabeticcondition'], 'string', 'max' => 200],
          //  [['age'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'profileId' => 'Profile ID',
            'firstName' => 'First Name',
            'userId' => 'User ID',
            'lastName' => 'Last Name',
            'gender' => 'Gender',
            'profilePic' => 'Profile Pic',
            'DOB' => 'Date Of Birth',
            'weight' => 'Weight',
            'height' => 'Height',
            'age' => 'Age',
            'familyhistory' => 'Familyhistory',
            'glucosescore' => 'Glucosescore',
            'diabeticcondition' => 'Diabeticcondition',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
        ];
    }
	 public static function getProfiles()
    {
        $model = Userprofile::find()->asArray()->all();
        $data = array();
        foreach($model as $key=>$value)
        {
            $data[$value['userId']] = $value['firstName'];
        }
        return $data;
    }
}
