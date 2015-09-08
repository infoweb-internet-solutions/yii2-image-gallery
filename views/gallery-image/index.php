<?php

use yii\helpers\Html;
use kartik\widgets\FileInput;
use kartik\grid\GridView;
use infoweb\cms\assets\ImageAsset;
use yii\helpers\Url;
use infoweb\gallery\GalleryAsset;

$this->title = Yii::t('app', 'Images');
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/gallery', 'Galleries'), 'url' => ['/gallery/gallery']];
$this->params['breadcrumbs'][] = ['label' => $gallery->name, 'url' => ['/gallery/gallery/update', 'id' => $gallery->id]];
$this->params['breadcrumbs'][] = $this->title;

ImageAsset::register($this);
GalleryAsset::register($this);

// Render growl messages
$this->render('_growl_messages');

?>

<div class="images-index">

    <h1><?= Yii::t('app', 'Add {modelClass}', ['modelClass' => strtolower(Yii::t('app', 'Images'))] ) ?></h1>

    <?= FileInput::widget([
        'id' => 'file-upload',
        'name' => 'ImageUploadForm[images][]',
        'options' => [
            'multiple' => true,
            'style' => 'margin-bottom: 30px',
            //'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'previewFileType' => 'any',
            'mainClass' => 'input-group-lg',
            'uploadUrl' => Url::to(['/gallery/gallery-image/upload']),
            'maxFileCount' => 100,
            'overwriteInitial' => false,
            'uploadAsync' => false,
            'dropZoneTitle' => Yii::t('infoweb/gallery', 'Drag & drop files here …'),
            'fileActionSettings' => [
                'uploadClass' => 'hide',
                'removeTitle' => Yii::t('infoweb/gallery', 'Remove file'),
                'uploadTitle' => Yii::t('infoweb/gallery', 'Upload file'),
                'indicatorNewTitle' => Yii::t('infoweb/gallery', 'Not uploaded yet'),
                'indicatorSuccessTitle' => Yii::t('infoweb/gallery', 'Uploaded'),
                'indicatorErrorTitle' => Yii::t('infoweb/gallery', 'Upload Error'),
                'indicatorLoadingTitle' => Yii::t('infoweb/gallery', 'Uploading ...'),
            ],
            'browseLabel' => Yii::t('infoweb/gallery', 'Browse'),
            'removeLabel' => Yii::t('infoweb/gallery', 'Remove'),
            'removeTitle' => Yii::t('infoweb/gallery', 'Remove selected files'),
            'uploadLabel' => Yii::t('infoweb/gallery', 'Upload'),
            'uploadTitle' => Yii::t('infoweb/gallery', 'Upload selected files'),
            'cancelLabel' => Yii::t('infoweb/gallery', 'Cancel'),           
        ],
    ]) ?>

    <?php // Title ?>
    <h1>
        <?= $this->title ?>
        <?php // Buttons ?>
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Sort {modelClass}', [
                'modelClass' => Yii::t('app', 'Images'),
            ]), ['sort'], ['class' => 'btn btn-success']) ?>

            <?= Html::button(Yii::t('app', 'Delete'), [
                'class' => 'btn btn-danger',
                'id' => 'batch-delete',
                'data-pjax' => 0,
                'style' => 'display: none;',
                'data-url' => Url::toRoute('gallery-image/multiple-delete-confirm-message'),
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
                            'title' => Yii::t('app', 'Set as main image'),
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
        'resizableColumns' => false,
        'pjaxSettings' => [
            'options' => [
                'id' => "grid-pjax",
            ],
        ],
    ]) ?>

</div>
