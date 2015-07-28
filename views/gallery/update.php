<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model infoweb\gallery\models\Gallery */

$this->title = Yii::t('infoweb/cms', 'Update {modelClass}', [
    'modelClass' => Yii::t('infoweb/gallery', 'Gallery'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/gallery', 'Galleries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('infoweb/cms', 'Update');

?>
<div class="gallery-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'         => $model,
    ]) ?>

</div>
