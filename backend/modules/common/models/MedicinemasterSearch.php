<?php

namespace backend\modules\common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\common\models\Medicinemaster;

/**
 * MedicinemasterSearch represents the model behind the search form of `backend\modules\common\models\Medicinemaster`.
 */
class MedicinemasterSearch extends Medicinemaster
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['medicineId', 'createdBy', 'updatedBy'], 'integer'],
            [['medicineName', 'drugName', 'createdDate', 'updatedDate', 'ipAddress','type','status'], 'safe'],
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
        $query = Medicinemaster::find();

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
            'medicineId' => $this->medicineId,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
        ]);

        $query->andFilterWhere(['like', 'medicineName', $this->medicineName])
            ->andFilterWhere(['like', 'drugName', $this->drugName])
			 ->andFilterWhere(['like', 'type', $this->type])
			  ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
