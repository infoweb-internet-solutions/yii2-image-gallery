<?php
namespace infoweb\gallery\widgets;

use Yii;
use yii\bootstrap\Widget;
use infoweb\gallery\models\Gallery as GalleryModel;

class Gallery extends Widget
{
    public $template = '_gallery';

    public function init()
    {
        parent::init();
    }

    /**
     * @return null|string
     */
    public function run()
    {

        $models = GalleryModel::find()->where(['active' => 1])->orderby(['position' => SORT_DESC])->all();

        return $this->render($this->template, ['models' => $models]);
    }
}
