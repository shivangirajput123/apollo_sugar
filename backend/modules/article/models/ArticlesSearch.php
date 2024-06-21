<?php

namespace backend\modules\article\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\article\models\Articles;

/**
 * ArticlesSearch represents the model behind the search form of `backend\modules\article\models\Articles`.
 */
class ArticlesSearch extends Articles
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['articleId', 'categoryId', 'createdBy', 'updatedBy'], 'integer'],
            [['categoryName', 'title', 'Description', 'Url', 'file', 'Status', 'createdDate', 'updatedDate', 'ipAddress'], 'safe'],
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
        $query = Articles::find();

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
            'articleId' => $this->articleId,
            'categoryId' => $this->categoryId,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdDate' => $this->createdDate,
            'updatedDate' => $this->updatedDate,
        ]);

        $query->andFilterWhere(['like', 'categoryName', $this->categoryName])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'Description', $this->Description])
            ->andFilterWhere(['like', 'Url', $this->Url])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress]);

        return $dataProvider;
    }
}
