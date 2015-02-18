<?php
use kartik\datecontrol\DateControl;
use kartik\widgets\SwitchInput;
?>
<div class="tab-content data-tab">

    <?php echo $form->field($model, 'active')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); ?>

    <?= $form->field($model, 'date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
    ]) ?>


</div>