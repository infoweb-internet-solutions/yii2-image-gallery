<?php

use yii\helpers\Html;
use infoweb\cms\assets\ImageAsset;
ImageAsset::register($this);

$this->title = Yii::t('app', 'Sort');
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/gallery', 'Galleries'), 'url' => ['/gallery/gallery']];
$this->params['breadcrumbs'][] = ['label' => $gallery->name, 'url' => ['/gallery/gallery/update', 'id' => $gallery->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/cms', 'Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Render growl messages
$this->render('_growl_messages');

?>
<div class="sort-form">
    <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger pull-right']) ?>
    <h1><?= $this->title ?></h1>

    <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Yii::t('app', 'Close'); ?></span></button>
        <?= Yii::t('app', 'Drag and drop the images to change the sort order') ?>
    </div>

    <div class="row" id="sortable">
        <?php foreach ($images as $key => $image): ?>
        <div id="<?= $image->id ?>" class="handle sortable-container col-xs-4 col-md-2">
            <img class="img-responsive center-block" src="<?php echo $image->getUrl('240x240'); ?>">
        </div>
        <?php endforeach; ?>
    </div>
</div>