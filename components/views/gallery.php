<?php
use yii\helpers\Html;
use infoweb\gallery\GalleryAsset;
GalleryAsset::register($this);

$html = '';
$html .= Html::beginTag('div', ['id' => 'blueimp-gallery', 'class' => 'blueimp-gallery  blueimp-gallery-controls']);
$html .= Html::tag('div', '', ['class' => 'slides']);
$html .= Html::tag('h3', '', ['class' => 'title']);
$html .= Html::tag('p', '', ['class' => 'description']);
$html .= Html::tag('a', '‹', ['class' => 'prev']);
$html .= Html::tag('a', '›', ['class' => 'next']);
$html .= Html::tag('a', '×', ['class' => 'close']);
$html .= Html::tag('a', '', ['class' => 'play-pause']);
$html .= Html::tag('ol', '', ['class' => 'indicator']);
$html .= Html::endTag('div');

echo $html;

/*
<div id="blueimp-gallery" class="blueimp-gallery  blueimp-gallery-controls" <!-- data options -->>
    <div class="slides"></div>
    <h3 class="title"></h3>
    <!-- The placeholder for the description label: -->
    <p class="description"></p>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
*/

