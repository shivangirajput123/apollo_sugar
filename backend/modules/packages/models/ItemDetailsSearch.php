<?php

namespace backend\modules\packages\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\packages\models\ItemDetails;

/**
 * ItemDetailsSearch represents the model behind the search form of `backend\modules\packages\models\ItemDetails`.
 */
class ItemDetailsSearch extends ItemDetails
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemId', 'discount', 'cityId', 'locationId', 'createdBy', 'updatedBy'], 'integer'],
            [['itemName', 'itemCode', 'aliasName', 'cityName', 'locationName', 'itemDescription', 'Status', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
            [['rate', 'offerPrice'], 'number'],
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
        $query = ItemDetails::find();

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
            'itemId' => $this->itemId,
            'rate' => $this->rate,
            'offerPrice' => $this->offerPrice,
            'discount' => $this->discount,
            'cityId' => $this->cityId,
            'locationId' => $this->locationId,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'itemName', $this->itemName])
            ->andFilterWhere(['like', 'itemCode', $this->itemCode])
            ->andFilterWhere(['like', 'aliasName', $this->aliasName])
            ->andFilterWhere(['like', 'cityName', $this->cityName])
            ->andFilterWhere(['like', 'locationName', $this->locationName])
            ->andFilterWhere(['like', 'itemDescription', $this->itemDescription])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
