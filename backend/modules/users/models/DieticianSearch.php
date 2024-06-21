<?php

namespace backend\modules\users\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\users\models\Dietician;
use Yii;
use backend\modules\clinics\models\Clinics;
/**
 * DieticianSearch represents the model behind the search form of `backend\modules\users\models\Dietician`.
 */
class DieticianSearch extends Dietician
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dieticianId', 'userId', 'cityId', 'locationId', 'createdBy', 'updatedBy'], 'integer'],
            [['cityName', 'locationName', 'dieticianName', 'email', 'dieticianDesription', 'profileImage', 'experience', 'qualification', 'membership', 'Status', 'createdDate', 'updatedDate', 'ipAddress', 'metaTitle', 'metaDescription', 'metaKeywords', 'seo_url'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		if(Yii::$app->user->identity->roleName != 'Clinic')
		{
			$query = Dietician::find();
		}
		else
		{
			$clinic = Clinics::find()->where(['userId'=>Yii::$app->user->id])->one();
			$query = Dietician::find()->where(['clinicId'=>$clinic->clinicId]);
		}
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'dieticianId' => $this->dieticianId,
            'userId' => $this->userId,
            'cityId' => $this->cityId,
            'locationId' => $this->locationId,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'cityName', $this->cityName])
            ->andFilterWhere(['like', 'locationName', $this->locationName])
            ->andFilterWhere(['like', 'dieticianName', $this->dieticianName])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'dieticianDesription', $this->dieticianDesription])
            ->andFilterWhere(['like', 'profileImage', $this->profileImage])
            ->andFilterWhere(['like', 'experience', $this->experience])
            ->andFilterWhere(['like', 'qualification', $this->qualification])
            ->andFilterWhere(['like', 'membership', $this->membership])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress])
            ->andFilterWhere(['like', 'metaTitle', $this->metaTitle])
            ->andFilterWhere(['like', 'metaDescription', $this->metaDescription])
            ->andFilterWhere(['like', 'metaKeywords', $this->metaKeywords])
            ->andFilterWhere(['like', 'seo_url', $this->seo_url]);

        return $dataProvider;
    }
}
