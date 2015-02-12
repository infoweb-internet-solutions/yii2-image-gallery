<?php

namespace infoweb\gallery\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use infoweb\gallery\models\Gallery;

/**
 * GallerySearch represents the model behind the search form about `infoweb\gallery\models\Gallery`.
 */
class GallerySearch extends Gallery
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Gallery::find()->joinWith(['translations'])->where(['language' => Yii::$app->language]);

        // Join the entity model as a relation
        $query->joinWith(['translations']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort' => ['defaultOrder' => ['manufacturer' => SORT_ASC]],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        // enable sorting for the related column
        $dataProvider->sort->attributes['name'] = [
            'asc' => ['translations.name' => SORT_ASC],
            'desc' => ['translations.name' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'translations.name', $this->name]);

        return $dataProvider;
    }
}
