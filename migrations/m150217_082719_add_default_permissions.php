<?php

use yii\db\Schema;
use yii\db\Migration;

class m150217_082719_add_default_permissions extends Migration
{
    public function up()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'showGalleryModule',
            'type'          => 2,
            'description'   => 'Show gallery module in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);

        // Create the auth item relation
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showGalleryModule'
        ]);
    }

    public function down()
    {
        // Delete the auth item relation

        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showGalleryModule'
        ]);

        // Delete the auth items
        $this->delete('{{%auth_item}}', [
            'name'          => 'showGalleryModule',
            'type'          => 2,
        ]);
    }
}
