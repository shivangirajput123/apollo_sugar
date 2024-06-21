<?php

namespace backend\modules\notifications\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\notifications\models\Notificationtypes;

/**
 * NotificationtypesSearch represents the model behind the search form of `backend\modules\notifications\models\Notificationtypes`.
 */
class NotificationtypesSearch extends Notificationtypes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notificationTypeId', 'createdBy', 'updatedBy'], 'integer'],
            [['type', 'description', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
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
        $query = Notificationtypes::find();

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
            'notificationTypeId' => $this->notificationTypeId,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
