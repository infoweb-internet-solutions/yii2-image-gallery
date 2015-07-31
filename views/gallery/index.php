<?php

use yii\helpers\Html;
use infoweb\sortable\SortableGridView;
use yii\helpers\Url;
use kartik\icons\Icon;
use infoweb\gallery\GalleryAsset;

/* @var $this yii\web\View */
/* @var $searchModel infoweb\gallery\models\GallerySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('infoweb/gallery', 'Galleries');
$this->params['breadcrumbs'][] = $this->title;

GalleryAsset::register($this);
Icon::map($this);

?>
<div class="gallery-index">

    <?php // Title ?>
    <h1>
        <?= Html::encode($this->title) ?>

        <?php // Buttons ?>
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => Yii::t('infoweb/gallery', 'Gallery'),
            ]), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </h1>

    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'orderUrl' => ['order'],
        'sortOptions' => [
            'containment' => '#grid-pjax .table',
            'cursor' => 'move',
            'handle' => '.handle',
        ],
        'columns' => [
            [
                'format' => 'raw',
                'value' => function ($model) {
                    return Icon::show('arrows-v', ['class' => 'handle handle-partners']);
                },
                'width' => '20px',
            ],
            'name',
            [
                'attribute'=>'date',
                'value'=>function ($model, $index, $widget) {
                    return Yii::$app->formatter->asDate($model->date);
                },
                'filterType' => \kartik\grid\GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ],
                'width' => '220px',
                'hAlign' => 'center',
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete} {active} {image} {duplicate}',
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
                    'image' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-picture"></span>', $url, [
                            'title' => Yii::t('app', 'Images'),
                            'data-pjax' => '0',
                            'data-toggle' => 'tooltip',
                        ]);
                    },
                ],
                'urlCreator' => function($action, $model, $key, $index) {
                    if ($action === 'image') {
                        return Url::toRoute(['/gallery/gallery-image/index', 'gallery-id' => $model->id]);
                    } else {
                        return Url::toRoute([$action, 'id' => $key]);
                    }
                },
                'updateOptions' => ['title' => Yii::t('app', 'Update'), 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['title' => Yii::t('app', 'Delete'), 'data-toggle' => 'tooltip'],
                'width' => '160px',
            ],
        ],
    ]); ?>

</div>