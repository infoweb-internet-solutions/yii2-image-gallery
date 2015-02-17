Please do not use this module

Image gallery extension for Yii 2
========================================
This extension provides an image gallery

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist infoweb-internet-solutions/yii2-image-gallery "*"
```

or add

```
"infoweb-internet-solutions/yii2-image-gallery": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed run this migration

```
yii migrate/up --migrationPath=@infoweb/gallery/migrations
```

And modify your backend configuration as follows:

```php
return [
    ...
    'modules' => [
        'gallery' => [
            'class' => 'infoweb\gallery\Module',
        ],
    ],
    ...
];
```

Import the translations and use category 'infoweb/gallery':
```
yii i18n/import @infoweb/gallery/messages
```