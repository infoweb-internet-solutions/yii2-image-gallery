<?php
namespace infoweb\gallery\assets;

use yii\web\AssetBundle as AssetBundle;

class BlueimpGalleryAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-gallery';

    public $css = [
        'css/blueimp-gallery.min.css',
    ];
    public $js = [
        'js/jquery.blueimp-gallery.min.js',
        'js/blueimp-gallery-fullscreen.js',
    ];
}
