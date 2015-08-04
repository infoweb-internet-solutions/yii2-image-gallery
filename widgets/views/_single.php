<?php
use yii\helpers\Html;
?>
<h2><?= $model->name ?></h2>

<?= Html::img($model->getImage()->getUrl('1000x'), ['class' => 'img-responsive center-block', 'alt' => $model->getImage()->alt, 'title' => $model->getImage()->title]) ?>

<p><?= $model->description ?></p>

<div class="row">
    <?php foreach ($model->getImages() as $image): ?>
    <div class="col-sm-6">

        <?= Html::a(
            Html::img($image->getUrl('200x200'), [
                'class' => 'img-responsive center-block thumbnail',
                'alt' => $image->alt,
                'title' => $image->title
            ]), $image->getUrl('1000x'), [
                'class' => 'fancybox',
                'rel' => 'group',
            ]) ?>
    </div>
    <?php endforeach; ?>
</div>
