<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use infoweb\gallery\GalleryAsset;

GalleryAsset::register($this);

/* @var $this yii\web\View */
/* @var $model infoweb\gallery\models\Gallery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gallery-form">

    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php
    // Init the form
    $form = ActiveForm::begin([
        'id'                        => 'gallery-form',
        'options'                   => ['class' => 'tabbed-form'],
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false
    ]);

    // Initialize the tabs
    $tabs = [];

    // Add the main tabs
    $tabs = [
        [
            'label' => Yii::t('app', 'General'),
            'content' => $this->render('_default_tab', ['model' => $model, 'form' => $form]),
            'active' => true,
        ],
        [
            'label' => Yii::t('app', 'Data'),
            'content' => $this->render('_data_tab', ['model' => $model, 'form' => $form]),
        ],
    ];

    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);
    ?>

    <div class="form-group buttons">
        <?= $this->render('@infoweb/cms/views/ui/formButtons', ['model' => $model]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
