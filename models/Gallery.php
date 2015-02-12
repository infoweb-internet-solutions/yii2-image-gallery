<?php

namespace infoweb\gallery\models;

use Yii;
use dosamigos\translateable\TranslateableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "gallery".
 *
 * @property string $id
 * @property integer $active
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Gallery extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'trans' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'name',
                    'description',
                ]
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ],
            'image' => [
                'class' => 'infoweb\cms\behaviors\ImageBehave',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('infoweb/cms', 'ID'),
            'active' => Yii::t('infoweb/cms', 'Active'),
            'created_at' => Yii::t('infoweb/cms', 'Created At'),
            'updated_at' => Yii::t('infoweb/cms', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Lang::className(), ['gallery_id' => 'id'])->from(['translations' => Lang::tableName()]);
    }

}
