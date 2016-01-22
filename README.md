Image gallery extension for Yii 2
========================================
This extension provides a way to manage image galleries for your website.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
php composer.phar require --prefer-dist infoweb-internet-solutions/yii2-image-gallery "*"
```

or add

```json
"infoweb-internet-solutions/yii2-image-gallery": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed run this migration

```bash
yii migrate/up --migrationPath=@infoweb/gallery/migrations
```

And modify `backend/config/main.php` as follows:

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

Import the translations and use category **infoweb/gallery**:
```
yii i18n/import @infoweb/gallery/messages
```


Configuration
-------------
All available configuration options are listed below with their default values.
___
##### allowContentDuplication (type: `boolean`, default: `true`)
If this option is set to `true`, the `duplicateable` jquery plugin is activated on all translateable attributes.
___
