<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model infoweb\cms\models\Image */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="image-form">
    <?php
    // Init the form
    $form = ActiveForm::begin([
        'id' => 'page-partial-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]);

    // Initialize the tabs
    $tabs = [];

    // Add the main tabs
    $tabs = [
        [
            'label' => Yii::t('infoweb/cms', 'General'),
            'content' => $this->render('_default_tab', ['model' => $model, 'form' => $form]),
            'active' => true,
        ],
        [
            'label' => Yii::t('infoweb/cms', 'Data'),
            'content' => $this->render('_data_tab', ['model' => $model, 'form' => $form]),
        ],
    ];
    
    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);
    ?>
    <div class="buttons form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
