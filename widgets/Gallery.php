<?php
namespace infoweb\gallery\widgets;

use Yii;
use yii\bootstrap\Widget;
use infoweb\gallery\models\Gallery as GalleryModel;

class Gallery extends Widget
{
    public $template = '_galleries';
    public $detailTemplate = '_gallery';
    public $class = 'col-sm-8';
    public $model = null;

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

        if (isset($this->model)) {
            return $this->render($this->detailTemplate, ['model' => $this->model, 'class' => $this->class]);
        } else {
            return $this->render($this->template, ['models' => $models, 'class' => $this->class]);
        }
    }
}
