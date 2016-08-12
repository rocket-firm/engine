<?php

use yii\db\Schema;
use yii\db\Migration;

class m123514_120816_addBannersTable extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('banners', [
            'id' => 'pk',
            'priority' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'content' => Schema::TYPE_STRING . ' NOT NULL',
            'start_date' => Schema::TYPE_DATE . ' NOT NULL',
            'end_date' => Schema::TYPE_DATE . ' DEFAULT NULL',
            'is_active' => Schema::TYPE_SMALLINT . ' DEFAULT 1',
            'url' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'image' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'type' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'swf' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'swf_width' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'swf_height' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'bg_color' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'create_date' => Schema::TYPE_TIMESTAMP . ' DEFAULT "0000-00-00 00:00:00"',
            'update_date' => Schema::TYPE_TIMESTAMP . ' DEFAULT "0000-00-00 00:00:00"'
        ], $tableOptions);

        $this->createIndex('is_active_index', 'banners', 'is_active');

        $this->createTable('banner_places', [
            'id' => 'pk',
            'banner_id' => Schema::TYPE_INTEGER,
            'place' => Schema::TYPE_INTEGER,
            'page' => Schema::TYPE_INTEGER
        ], $tableOptions);

        $this->createIndex('banner_id_index', 'banner_places', 'banner_id');
        $this->addForeignKey('banner_id_fk', 'banner_places', 'banner_id', 'banners', 'id');
    }

    public function down()
    {
        $this->dropTable('banners');
    }
}
