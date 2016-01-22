<?php
use yii\helpers\Html;
?>
<?php if ($models): ?>
<div class="row">
    <?php foreach ($models as $model): ?>
    <?php if ($model->getImage(false)): ?>
    <div class="<?= $class ?>">
        <div class="title"><?= $model->name ?></div>
        <?= Html::a(
            Html::img($model->getImage(false)->getUrl('400x250'), ['class' => 'img-responsive center-block', 'alt' => $model->getImage()->alt, 'title' => $model->getImage()->title]),
            $model->getUrl(false),
            ['class' => 'thumbnail']
        ) ?>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>