<?php

namespace backend\modules\packages\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\packages\models\Plans;

/**
 * PlansSearch represents the model behind the search form of `backend\modules\packages\models\Plans`.
 */
class PlansSearch extends Plans
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planId', 'discount',  'createdBy', 'updatedBy'], 'integer'],
            [['PlanName', 'aliasName', 'tenture',  'locationName','duration', 'Status', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
            [['Price', 'offerPrice'], 'number'],
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
		if(Yii::$app->user->identity->roleId == 1)
		{
			$query = Plans::find();
		}
		else
		{
			$query = Plans::find()->where(['createdBy'=>Yii::$app->user->id]);
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
            'planId' => $this->planId,
            'Price' => $this->Price,
            'offerPrice' => $this->offerPrice,
            'discount' => $this->discount,
            
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'PlanName', $this->PlanName])
            ->andFilterWhere(['like', 'aliasName', $this->aliasName])
            ->andFilterWhere(['like', 'tenture', $this->tenture])
          //  ->andFilterWhere(['like', 'cityName', $this->cityName])
            ->andFilterWhere(['like', 'locationName', $this->locationName])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
