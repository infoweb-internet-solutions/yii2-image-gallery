<?php

use yii\helpers\Html;
use kartik\widgets\FileInput;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use infoweb\cms\assets\ImageAsset;
use yii\helpers\Url;

ImageAsset::register($this);

$this->title = Yii::t('infoweb/cms', 'Images');
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/ecommerce', 'Products'), 'url' => ['/catalogue/product']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['/catalogue/product/update', 'id' => $product->id]];
$this->params['breadcrumbs'][] = $this->title;

// Render growl messages
$this->render('_growl_messages');

?>

<div class="images-index">

    <h1><?= Yii::t('app', 'Add {modelClass}', ['modelClass' => strtolower(Yii::t('infoweb/cms', 'Images'))] ) ?></h1>

    <?php $form = ActiveForm::begin(['action' => ['/catalogue/product-image/upload'], 'options' => [ 'class' => 'image-upload-form', 'enctype' => 'multipart/form-data']]); ?>

    <?php if ($product->hasErrors()) { //it is necessary to see all the errors for all the files. @todo Show growl message
        echo '<pre>';
        print_r($product->getErrors());
        echo '</pre>';
    } ?>

    <?= FileInput::widget([
        'name' => 'ImageUploadForm[images][]',
        'options' => [
            'multiple' => true,
            //'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'previewFileType' => 'any',
            'mainClass' => 'input-group-lg',
        ],
    ]) ?>

    <?php ActiveForm::end(); ?>

    <?php // Title ?>
    <h1>
        <?= $this->title ?>
        <?php // Buttons ?>
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Sort {modelClass}', [
                'modelClass' => Yii::t('infoweb/cms', 'Images'),
            ]), ['sort'], ['class' => 'btn btn-success']) ?>
    
            <?= Html::button(Yii::t('app', 'Delete'), [
                'class' => 'btn btn-danger',
                'id' => 'batch-delete',
                'data-pjax' => 0,
                'style' => 'display: none;'
            ]) ?>    
        </div>
    </h1>

    <?php // Gridview ?>
    <?= GridView::widget([
        'id' => 'gridview',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => '\kartik\grid\CheckboxColumn'
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('app', 'Image'),
                'hAlign' => GridView::ALIGN_CENTER,
                'value' => function($data) { return $data->popupImage; },
                'width' => '96px',

            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => Yii::t('app', 'Name'),
                'value' => function($data) { return $data->name; },
                'vAlign' => GridView::ALIGN_MIDDLE,
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete} {active} {main}',
                'buttons' => [
                    'active' => function ($url, $model) {
                        if ($model->active == true) {
                            $icon = 'glyphicon-eye-open';
                        } else {
                            $icon = 'glyphicon-eye-close';
                        }

                        return Html::a('<span class="glyphicon ' . $icon . '"></span>', $url, [
                            'title' => Yii::t('app', 'Toggle active'),
                            'data-pjax' => '0',
                            'data-toggleable' => 'true',
                            'data-toggle-id' => $model->id,
                            'data-toggle' => 'tooltip',
                        ]);
                    },
                    'main' => function ($url, $model) {
                        if ($model->isMain == true)
                            return '<span class="glyphicon glyphicon-star icon-blue"></span>';

                        return Html::a('<span class="glyphicon glyphicon-star icon-gray"></span>', $url, [
                            'title' => Yii::t('infoweb/cms', 'Set as main image'),
                            'data-pjax' => '0',
                            'data-toggleable' => 'true',
                            'data-toggle-id' => $model->id,
                            'data-toggle' => 'tooltip',
                        ]);
                    },
                ],
                'updateOptions' => ['title' => Yii::t('app', 'Update'), 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['title' => Yii::t('app', 'Delete'), 'data-toggle' => 'tooltip'],
                'width' => '140px',
                'vAlign' => GridView::ALIGN_MIDDLE,
            ],
        ],
        'responsive' => true,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => 88],
        'hover' => true,
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                'id' => "grid-pjax",
            ],
        ],
    ]) ?>

</div>
