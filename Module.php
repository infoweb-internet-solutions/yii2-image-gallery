<?php

namespace infoweb\gallery;

use Yii;

class Module extends \yii\base\Module
{
    public $urlPrefix = 'fotogalerij';

    /**
     * Allow content duplication with the "duplicateable" plugin
     * @var boolean
     */
    public $allowContentDuplication = true;

    public function init()
    {
        parent::init();

        Yii::configure($this, require(__DIR__ . '/config.php'));

        // Content duplication is only possible if there is more than 1 app language
        if (isset(Yii::$app->params['languages']) && count(Yii::$app->params['languages']) == 1) {
            $this->allowContentDuplication = false;
        }
    }
}