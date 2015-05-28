<?php

use yii\db\Schema;
use yii\db\Migration;

class m150211_094318_init extends Migration
{
    public function up()
    {

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * Gallery
        */

        // Create 'gallery' table
        $this->createTable('{{%gallery}}', [
            'id'                    => Schema::TYPE_PK,
            'date'                  => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'thumbnail_width'       => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'250\'',
            'thumbnail_height'      => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'150\'',
            'active'                => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT \'1\'',
            'position'              => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT \'0\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        // Create 'gallery_lang' table
        $this->createTable('{{%gallery_lang}}', [
            'gallery_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'          => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'              => Schema::TYPE_STRING . '(255) NOT NULL',
            'description'       => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'        => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('gallery_id_language', '{{%gallery_lang}}', ['gallery_id', 'language']);
        $this->createIndex('language', '{{%gallery_lang}}', 'language');
        $this->addForeignKey('FK_GALLERY_LANG_GALLERY_ID', '{{%gallery_lang}}', 'gallery_id', '{{%gallery}}', 'id', 'CASCADE', 'NO ACTION');

    }

    public function down()
    {
        $this->dropTable('gallery_lang');
        $this->dropTable('gallery');
    }
}