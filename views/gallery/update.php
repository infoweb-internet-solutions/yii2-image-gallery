<?php

use yii\helpers\Html;
use infoweb\catalogue\CatalogueAsset;

/* @var $this yii\web\View */
/* @var $model infoweb\catalogue\models\product\Product */

$this->title = Yii::t('ecommerce', 'Update {modelClass}', [
    'modelClass' => 'Product',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('ecommerce', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('ecommerce', 'Update');

CatalogueAsset::register($this);

?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'         => $model,
    ]) ?>

</div>
