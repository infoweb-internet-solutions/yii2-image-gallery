<?php

use yii\helpers\Html;
use infoweb\catalogue\CatalogueAsset;

/* @var $this yii\web\View */
/* @var $model infoweb\catalogue\models\product\Product */

$this->title = Yii::t('ecommerce', 'Create {modelClass}', [
    'modelClass' => 'Product',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('ecommerce', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

CatalogueAsset::register($this);

?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'         => $model,
    ]) ?>

</div>
