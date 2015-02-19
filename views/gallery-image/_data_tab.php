<?php
use kartik\widgets\SwitchInput;
?>
<div class="tab-content data-tab">

    <div class="form-group">
        <img class="img-responsive" src="<?php echo $model->getUrl('350x'); ?>">
    </div>

    <?php echo $form->field($model, 'active')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); ?>

    <?php /*
    @todo Add main image switch
    <?php echo $form->field($model, 'isMain')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); ?>
    */ ?>

</div>