<?php
namespace infoweb\gallery\widgets;

use Yii;
use yii\bootstrap\Widget;
use infoweb\gallery\models\Gallery as GalleryModel;

class Gallery extends Widget
{
    public $template = '_list';
    public $detailTemplate = '_single';
    public $class = 'col-sm-8';

    public function init()
    {
        parent::init();

    }

    /**
     * Get the requested model
     *
     * @return array|null|\yii\db\ActiveRecord|static
     */
    private function findModel()
    {
        $model = null;

        if (Yii::$app->request->get('slug', null) !== null) {
            $model = GalleryModel::find()->joinWith('translations')->where(['slug' => Yii::$app->request->get('slug'), 'active' => 1])->one();
        }

        return $model;
    }

    /**
     * @return null|string
     */
    public function run()
    {
        $model = $this->findModel();

        if (isset($model)) {
            return $this->render($this->detailTemplate, ['model' => $model, 'class' => $this->class]);
        } else {
            $models = GalleryModel::find()->where(['active' => 1])->orderby(['position' => SORT_DESC])->all();
            return $this->render($this->template, ['models' => $models, 'class' => $this->class]);
        }
    }
}
