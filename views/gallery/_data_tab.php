<?php
use kartik\datecontrol\DateControl;
use kartik\widgets\SwitchInput;
?>
<div class="tab-content data-tab">

    <?= $form->field($model, 'date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
    ]) ?>

    <?php if (Yii::$app->user->can('Superadmin')) : ?>
    <?= $form->field($model, 'thumbnail_width')->textInput() ?>

    <?= $form->field($model, 'thumbnail_height')->textInput() ?>
    <?php endif; ?>

    <?php echo $form->field($model, 'active')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); ?>

</div>