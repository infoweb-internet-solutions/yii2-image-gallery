<?php

?>
<div class="tab-content default-language-tab">
    <?= $form->field($model, "[{$model->language}]alt")->textInput([
        'maxlength' => 255,
        'name' => "ImageLang[{$model->language}][alt]",
    ]); ?>
    <?= $form->field($model, "[{$model->language}]title")->textInput([
        'maxlength' => 255,
        'name' => "ImageLang[{$model->language}][title]"
    ]); ?>
    <?= $form->field($model, "[{$model->language}]description")->textarea([
        'name' => "ImageLang[{$model->language}][description]"
    ]); ?>

</div>
