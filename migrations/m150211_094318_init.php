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
         * Attributes


        // Create 'ecommerce_attributes' table
        $this->createTable('{{%ecommerce_attributes}}', [
            'id'                    => Schema::TYPE_PK,
            'group_id'              => Schema::TYPE_INTEGER . ' NOT NULL',
            'translateable'         => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'position'              => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT \'0\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);
         */
    }

    public function down()
    {
        //$this->dropTable('ecommerce_options_lang');
    }
}