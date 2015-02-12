<?php
namespace infoweb\gallery;

use yii\web\AssetBundle as AssetBundle;

class GalleryAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/gallery/assets/';
    
    public $css = [
        'css/main.css',
    ];
    
    public $js = [
        'js/main.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'infoweb\gallery\assets\BlueimpGalleryAsset',
    ];
}
