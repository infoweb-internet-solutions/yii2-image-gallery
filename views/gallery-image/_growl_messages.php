<?php

use kartik\widgets\Growl;

if (Yii::$app->session->hasFlash('image-success'))
{
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::$app->session->getFlash('image-success'),
    ]);
}
