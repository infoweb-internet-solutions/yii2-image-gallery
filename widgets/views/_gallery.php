<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php foreach ($models as $model): ?>
<div class="<?= $class ?>">
    <a href="<?= Url::to('fotogalerij/' . \yii\helpers\Inflector::slug($model->name)) ?>">
        <?= Html::img($model->getImage()->getUrl('260x170'), ['class' => 'img-responsive center-block', 'alt' => $model->getImage()->alt, 'title' => $model->getImage()->title]) ?>
        <?= $model->name ?>
    </a>
</div>
<?php endforeach; ?>