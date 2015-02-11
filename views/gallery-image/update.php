<?php
use yii\helpers\Html;
use infoweb\cms\assets\ImageAsset;

ImageAsset::register($this);

$this->title = Yii::t('app', 'Update {modelClass}', [
    'modelClass' => Yii::t('infoweb/sliders', 'Image'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/sliders', 'Sliders'), 'url' => ['/sliders']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['/sliders/default/update', 'id' => $product->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/sliders', 'Images'), 'url' => ['index', 'sliderId' => $product->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="image-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'slider' => $product,
    ]) ?>
</div>