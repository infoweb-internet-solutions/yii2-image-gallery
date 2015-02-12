<?php
namespace infoweb\gallery\assets;

use yii\web\AssetBundle as AssetBundle;

class BlueimpBootstrapGalleryAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-bootstrap-image-gallery';

    public $css = [
        'css/bootstrap-image-gallery.min.css',
    ];
    public $js = [
        'js/bootstrap-image-gallery.min.js'
    ];

    public $depends = [
        'infoweb\gallery\assets\BlueimpGalleryAsset',
    ];
}
