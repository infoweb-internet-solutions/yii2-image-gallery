<?php
use yii\helpers\Html;
?>
<?php foreach ($models as $model): ?>
<div class="<?= $class ?>">
    <a href="<?= $model->getUrl((Yii::$app->language == 'nl') ? false: true) ?>">
        <?= Html::img($model->getImage()->getUrl('260x170'), ['class' => 'img-responsive center-block', 'alt' => $model->getImage()->alt, 'title' => $model->getImage()->title]) ?>
        <?= $model->name ?>
    </a>
</div>
<?php endforeach; ?>