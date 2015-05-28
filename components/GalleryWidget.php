<?php
namespace infoweb\gallery\components;

use yii\base\Widget;
use yii\helpers\Html;

class GalleryWidget extends Widget
{
    public $images;
    public $gallery;
    public $size;
    public $classes;

    public function init()
    {
        parent::init();

        if ($this->images === null) {
            $this->images = [];
        }

        if ($this->gallery === null) {
            $this->gallery = [];
        }

        if ($this->size === null) {
            $this->size = '1000x';
        }

        if ($this->classes === null) {
            $this->classes = 'col-xs-8 col-sm-6 col-md-6 col-lg-4';
        }
    }

    public function run()
    {
        $html = '';
        $html .= Html::beginTag('div', ['id' => 'links', 'class' => 'row']);

        // @todo Move to view

        foreach ($this->images as $image) {

            $html .= Html::beginTag('div', [
                'class' => $this->classes,
                'style' => 'margin-bottom: 30px;',
            ]);

            $html .= Html::a(
                Html::img($image->getUrl("{$this->gallery->thumbnail_width}x{$this->gallery->thumbnail_height}"), [
                    'alt' => $image->alt,
                    'data-description' => $image->description,
                    'class' => 'img-responsive img-rounded'
                ]), $image->getUrl($this->size), [
                    'title' => $image->title,
                    'data-gallery' => true,
                    'data-description' => $image->description,
                    //'class' => 'thumbnail',
                ]
            );
            $html .= Html::endTag('div');
        }

        $html .= Html::endTag('div');

        $html .= $this->render('gallery');

        return $html;
    }
}

