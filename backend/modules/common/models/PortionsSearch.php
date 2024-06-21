<?php

namespace backend\modules\common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\common\models\Portions;

/**
 * PortionsSearch represents the model behind the search form of `backend\modules\common\models\Portions`.
 */
class PortionsSearch extends Portions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['portionId', 'createdBy', 'updatedBy'], 'integer'],
            [['portionName', 'Status', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
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
        $query = Portions::find();

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
            'portionId' => $this->portionId,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
        ]);

        $query->andFilterWhere(['like', 'portionName', $this->portionName])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
