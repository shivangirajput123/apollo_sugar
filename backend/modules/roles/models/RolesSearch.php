<?php

namespace backend\modules\roles\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\roles\models\Roles;

/**
 * RolesSearch represents the model behind the search form of `backend\modules\roles\models\Roles`.
 */
class RolesSearch extends Roles
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['roleId', 'createdBy', 'updatedBy'], 'integer'],
            [['roleName', 'roleDes', 'createdDate', 'updatedDate', 'ipAddress', 'Status'], 'safe'],
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
        $query = Roles::find();

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
            'roleId' => $this->roleId,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'roleName', $this->roleName])
            ->andFilterWhere(['like', 'roleDes', $this->roleDes])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress])
            ->andFilterWhere(['like', 'Status', $this->Status]);

        return $dataProvider;
    }
}
