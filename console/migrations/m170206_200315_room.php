<?php

use yii\db\Migration;

class m170206_200315_room extends Migration
{
    public function up()
    {
	    $this->createTable('room', [
		    'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
		    'name' => 'varchar(64) NOT NULL',
		    'access_level' => 'int(1) unsigned NOT NULL',
		    'image_id' => 'int(10) unsigned NOT NULL',
		    'token' => 'varchar(32) NULL',
		    'created_by' => 'int(11) NULL',
		    'PRIMARY KEY (`id`)'
	    ]);

	    $this->addForeignKey('FK', 'room', 'image_id', 'imagemanager', 'id');
	    $this->addForeignKey('FK_TO_USER', 'room', 'created_by', 'user', 'id');
    }

    public function down()
    {
        echo "m170206_200937_room cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
