<?php
use yii\helpers\Html;
?>
<?php foreach ($models as $model): ?>
<?php if ($model->getImage(false)): ?>
<div class="<?= $class ?>">
    <a href="<?= $model->getUrl(false) ?>">
        <?= Html::img($model->getImage(false)->getUrl('260x170'), ['class' => 'img-responsive center-block', 'alt' => $model->getImage()->alt, 'title' => $model->getImage()->title]) ?>
        <?= $model->name ?>
    </a>
</div>
<?php endif; ?>
<?php endforeach; ?>