<?php

namespace infoweb\gallery\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "gallery_lang".
 *
 * @property string $gallery_id
 * @property string $language
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Gallery $gallery
 */
class Lang extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery_lang';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['language'], 'required'],
            // Only required for existing records
            [['gallery_id'], 'required', 'when' => function($model) {
                return !$model->isNewRecord;
            }],
            // Only required for the app language
            [['name'], 'required', 'when' => function($model) {
                return $model->language == Yii::$app->language;
            }],
            // Trim
            [['name', 'description'], 'trim'],
            // Types
            [['gallery_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['language'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 255],
            // @todo Translate message
            [['gallery_id', 'language'], 'unique', 'targetAttribute' => ['gallery_id', 'language'], 'message' => 'The combination of Gallery ID and Language has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gallery' => Yii::t('infoweb/gallery', 'Gallery'),
            'language' => Yii::t('infoweb/cms', 'Language'),
            'name' => Yii::t('infoweb/cms', 'Name'),
            'description' => Yii::t('infoweb/cms', 'Description'),
            'created_at' => Yii::t('infoweb/cms', 'Created At'),
            'updated_at' => Yii::t('infoweb/cms', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(Gallery::className(), ['id' => 'gallery_id']);
    }
}
