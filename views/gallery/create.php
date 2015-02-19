<?php

use yii\helpers\Html;
use infoweb\gallery\GalleryAsset;

/* @var $this yii\web\View */
/* @var $model infoweb\gallery\models\Gallery */

$this->title = Yii::t('infoweb/cms', 'Create {modelClass}', [
    'modelClass' => Yii::t('infoweb/gallery', 'Gallery'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/gallery', 'Galleries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

GalleryAsset::register($this);

?>
<div class="gallery-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'         => $model,
    ]) ?>

</div>
