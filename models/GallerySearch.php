<?php

namespace infoweb\gallery\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use infoweb\gallery\models\Gallery;
use yii\helpers\StringHelper;

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
            [['name', 'date'], 'safe'],
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

        $query->orderBy('position');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // Convert date to Unix timestamp
        if (!empty($params[StringHelper::basename(self::className())]['date'])) {
            $query->andFilterWhere(['date' => strtotime($params[StringHelper::basename(self::className())]['date'])]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'translations.name', $this->name]);

        return $dataProvider;
    }
}
