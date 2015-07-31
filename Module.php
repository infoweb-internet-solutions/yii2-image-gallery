<?php

namespace infoweb\gallery;

use Yii;

class Module extends \yii\base\Module
{

    public $urlPrefix = 'fotogalerij';

    public function init()
    {
        parent::init();

        Yii::configure($this, require(__DIR__ . '/config.php'));


    }
}