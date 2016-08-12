<?php

use yii\db\Schema;
use yii\db\Migration;

class m123243_120816_initEngine extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        // Language table
        $this->createTable('languages', [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'code' => Schema::TYPE_STRING . ' NOT NULL',
            'locale' => Schema::TYPE_STRING . ' NOT NULL',
            'is_active' => Schema::TYPE_SMALLINT . ' DEFAULT 1'
        ], $tableOptions);

        // default language
        $this->insert('languages', ['title' => 'Русский', 'code' => 'ru', 'locale' => 'ru', 'is_active' => 1]);
        
        //Config table
        $this->createTable('config', [
            'id' => 'pk',
            'param' => Schema::TYPE_STRING . ' NOT NULL',
            'value' => Schema::TYPE_STRING . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL'
        ], $tableOptions);

        // menu tables
        $this->createTable('menus', [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'is_active' => Schema::TYPE_SMALLINT . ' DEFAULT 1',
        ], $tableOptions);

        $this->createIndex('active_menu', 'menus', 'is_active');

        $this->createTable('menu_items', [
            'id' => 'pk',
            'menu_id' => Schema::TYPE_INTEGER,
            'lang_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING,
            'type' => Schema::TYPE_STRING,
            'link' => Schema::TYPE_STRING,
            'params' => Schema::TYPE_STRING,
            'is_new_window' => Schema::TYPE_SMALLINT,
            'is_active' => Schema::TYPE_SMALLINT,
            'create_date' => Schema::TYPE_TIMESTAMP,
            'update_date' => Schema::TYPE_TIMESTAMP,
            'parent_id' => Schema::TYPE_INTEGER,
            'root' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER,
            'rgt' => Schema::TYPE_INTEGER,
            'level' => Schema::TYPE_INTEGER
        ], $tableOptions);

        $this->createIndex('menu_id_index', 'menu_items', 'menu_id');
        $this->createIndex('lang_id_index', 'menu_items', 'lang_id');
        $this->createIndex('new_window', 'menu_items', 'is_new_window');
        $this->createIndex('active_index', 'menu_items', 'is_active');

        $this->addForeignKey('menu_fk', 'menu_items', 'menu_id', 'menus', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('lang_fk', 'menu_items', 'lang_id', 'languages', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('parent_index', 'menu_items', 'parent_id');
        $this->createIndex('root_index', 'menu_items', 'root');
        $this->createIndex('lft_index', 'menu_items', 'lft');
        $this->createIndex('rgt_index', 'menu_items', 'rgt');
        $this->createIndex('level_index', 'menu_items', 'level');
    }

    public function down()
    {
        $this->dropTable('menu_items');
        $this->dropTable('menus');
        $this->dropTable('config');
        $this->dropTable('languages');
    }
}   