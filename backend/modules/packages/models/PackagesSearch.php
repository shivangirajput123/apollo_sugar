<?php

namespace backend\modules\packages\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\packages\models\Packages;

/**
 * PackagesSearch represents the model behind the search form of `backend\modules\packages\models\Packages`.
 */
class PackagesSearch extends Packages
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['packageId', 'createdBy', 'updatedBy'], 'integer'],
            [['packageName', 'packageDes', 'Status',  'LocationName', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
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
        $query = Packages::find();

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
            'packageId' => $this->packageId,
           
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'packageName', $this->packageName])
            ->andFilterWhere(['like', 'packageDes', $this->packageDes])
            ->andFilterWhere(['like', 'Status', $this->Status])
            //->andFilterWhere(['like', 'cityName', $this->cityName])
            ->andFilterWhere(['like', 'LocationName', $this->LocationName])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
