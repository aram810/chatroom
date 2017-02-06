<?php

use yii\db\Migration;

class m170206_201031_message extends Migration
{
    public function up()
    {
	    $this->createTable(
		    'message',
		    [
			    'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
			    'user_id' => 'int(11) DEFAULT NULL',
			    'user_session_id' => 'varchar(64) DEFAULT NULL',
			    'room_id' => 'int(11) unsigned NOT NULL',
			    'text' => 'varchar(2048) NOT NULL',
			    'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
			    'PRIMARY KEY (`id`)'
		    ]
	    );

	    $this->addForeignKey('FK1', 'message', 'user_id', 'user', 'id');
	    $this->addForeignKey('FK2', 'message', 'room_id', 'room', 'id');
    }

    public function down()
    {
        echo "m170206_201031_message cannot be reverted.\n";

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
