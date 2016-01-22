<?php
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use infoweb\cms\helpers\LanguageHelper;
?>
<div class="tab-content default-language-tab">
    <?= $form->field($model, "[{$model->language}]name")->textInput([
        'maxlength' => 255,
        'name' => "Lang[{$model->language}][name]",
        'data-duplicateable' => Yii::$app->getModule('gallery')->allowContentDuplication ? 'true' : 'false'
    ]); ?>

    <?php if (Yii::$app->getModule('gallery')->enableDescription): ?>
    <?= $form->field($model, "[{$model->language}]description")->widget(CKEditor::className(), [
        'name' => "Lang[{$model->language}][description]",
        'editorOptions' => ArrayHelper::merge(Yii::$app->getModule('cms')->getCKEditorOptions(), (LanguageHelper::isRtl($model->language)) ? ['contentsLangDirection' => 'rtl'] : []),
        'options' => ['data-duplicateable' => Yii::$app->getModule('gallery')->allowContentDuplication ? 'true' : 'false'],
    ]); ?>
    <?php endif; ?>
</div>
