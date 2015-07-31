<?php

namespace infoweb\gallery\models;

use Yii;
use dosamigos\translateable\TranslateableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use infoweb\sortable\Sortable;

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
                    'slug',
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
            'sortable' => [
                'class' => Sortable::className(),
                'orderAttribute' => ['position'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active', 'created_at', 'updated_at', 'position', 'date'], 'integer'],
            [['date'], 'default', 'value' => '0'],
            [['thumbnail_width'], 'default', 'value' => '250'],
            [['thumbnail_height'], 'default', 'value' => '150'],
            [['thumbnail_width', 'thumbnail_height'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => Yii::t('app', 'Date'),
            'name' => Yii::t('app', 'Name'),
            'thumbnail_width' => Yii::t('infoweb/gallery', 'Thumbnail width'),
            'thumbnail_height' => Yii::t('infoweb/gallery', 'Thumbnail height'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Lang::className(), ['gallery_id' => 'id'])->from(['translations' => Lang::tableName()]);
    }

    /**
     * Returns the url of the page
     *
     * @param   string  $includeLanguage
     * @return  string  $url
     */
    public function getUrl($includeLanguage = true)
    {
        $url = (Yii::$app->id !== 'app-backend') ? Yii::getAlias('@baseUrl') . '/' : '';

        if ($includeLanguage)
            $url .= (($this->language == null) ? Yii::$app->language : $this->language) . '/';

        $module = Yii::$app->getModule('gallery');

        if ($module->urlPrefix) {
            $url .= Yii::t('url', $module->urlPrefix) . '/';
        }

        $url .= $this->slug;

        return $url;
    }

}
