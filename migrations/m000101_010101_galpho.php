<?php

use yii\db\Schema;

class m000101_010101_galpho extends \yii\db\Migration
{
    public function up()
    {

        $this->createTable('tbl_user', array(
                'id' => 'pk',
                'username' => 'VARCHAR(64) NULL',
                'email' => 'VARCHAR(100) NULL',
                'validated' => 'boolean DEFAULT 0',
                'active' => 'boolean DEFAULT 0',
                'superuser' => 'boolean DEFAULT 0',
                'create' => 'datetime',
                'last_login' => 'datetime',
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->insert('tbl_user', array(
            'id' => 1,
            'username' => 'admin',
            'active' => 1,
            'validated' => 1,
            'superuser' => 1,
            'create' => new \yii\db\Expression('NOW()'),
        ));


        $this->createTable('tbl_user_authenticate', array(
                'id' => 'pk',
                'user_id' => 'integer NOT NULL',
                'provider' => 'VARCHAR(64) NULL',
                'identifier' => 'VARCHAR(256) NULL',
                'authenticate' => 'VARCHAR(512) NULL',
                'user_data' => 'text',
                'expire' => 'datetime NULL',
                'active' => 'boolean DEFAULT 0',
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->createIndex('i_user_id', 'tbl_user_authenticate', 'user_id', false);
        $this->addForeignKey('fk_user_authenticate_user_id', 'tbl_user_authenticate',
            'user_id', 'tbl_user', 'id', 'CASCADE', 'CASCADE');


        $this->createTable('tbl_user_field', array(
                'id' => 'pk',
                'user_id' => 'integer NOT NULL',
                'field' => 'VARCHAR(64) NOT NULL',
                'value' => 'text',
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->addForeignKey('fk_user_field_user_id', 'tbl_user_field', 'user_id',
            'tbl_user', 'id', 'CASCADE', 'CASCADE');


        $this->createTable('tbl_auth_item', array(
                'name' => 'VARCHAR(64) NOT NULL PRIMARY KEY',
                'type' => 'integer NOT NULL',
                'description' => 'text',
                'biz_rule' => 'text',
                'data' => 'text',
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->createIndex('i_type', 'tbl_auth_item', 'type', false);


        $this->createTable('tbl_auth_item_child', array(
                'parent' => 'VARCHAR(64) NOT NULL',
                'child' => 'VARCHAR(64) NOT NULL',
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->execute('ALTER TABLE `tbl_auth_item_child` ADD PRIMARY KEY ( `parent` , `child` )');
        $this->addForeignKey('fk_parent', 'tbl_auth_item_child', 'parent', 'tbl_auth_item', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_child', 'tbl_auth_item_child', 'child', 'tbl_auth_item', 'name', 'CASCADE', 'CASCADE');


        $this->createTable('tbl_auth_assignment', array(
                'item_name' => 'VARCHAR(64) NOT NULL',
                'user_id' => 'VARCHAR(64) NOT NULL PRIMARY KEY',
                'biz_rule' => 'text',
                'data' => 'text'),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->addForeignKey('fk_item_name', 'tbl_auth_assignment', 'item_name', 'tbl_auth_item', 'name', 'CASCADE', 'CASCADE');




        $this->createTable('gal_dir', array(
                'id' => 'pk',
                'element_id_cover' => 'integer',
                'path' => 'text',
                'title' => 'VARCHAR(256) NULL',
                'description' => 'text NULL',
                'create_time' => 'datetime NULL',
                'update_time' => 'datetime NULL',
                'sort_order' => 'VARCHAR(30) NULL',
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->createIndex('i_path', 'gal_dir', 'path(255)', true);

        $this->insert('gal_dir', array(
            'id' => 1,
            'element_id_cover' => null,
            'path' => '/',
            'title' => '',
            'create_time' => new \yii\db\Expression('NOW()'),
            'update_time' => new \yii\db\Expression('NOW()'),
        ));

        $this->createTable('gal_element', array(
                'id' => 'pk',
                'dir_id' => 'integer NOT NULL',
                'name' => 'VARCHAR(128) NULL',
                'title' => 'VARCHAR(256) NULL',
                'description' => 'text NULL',
                'create_time' => 'datetime NULL',
                'update_time' => 'datetime NULL',
                'format' => 'VARCHAR(10) NOT NULL',
                'rank' => 'integer DEFAULT 0',
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');


        //       $this->addForeignKey('fk_gal_dir_element_id_cover', 'gal_dir',
        //           'element_id_cover', 'gal_element', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_gal_element_dir_id', 'gal_element',
            'dir_id', 'gal_dir', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('gal_group', array(
                'id' => 'pk',
                'permanent' => 'boolean default 0',
                'name' => 'VARCHAR(128) NULL',
                'description' => 'text NULL',
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');


        $this->createTable('gal_group_user', array(
                'group_id' => 'integer',
                'user_id' => 'integer',
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addForeignKey('fk_gal_group_user_group_id', 'gal_group_user',
            'group_id', 'gal_group', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_gal_group_user_user_id', 'gal_group_user',
            'user_id', 'tbl_user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('gal_right', array(
                'group_id' => 'integer',
                'dir_id' => 'integer',
                'value'=>'integer NOT NULL DEFAULT 0'
            ),
            'ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->addForeignKey('fk_gal_right_group_id', 'gal_right',
            'group_id', 'gal_group', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_gal_right_dir_id', 'gal_right',
            'dir_id', 'gal_dir', 'id', 'CASCADE', 'CASCADE');

        return true;
    }

    public function down()
    {
        //     $this->dropForeignKey('fk_gal_dir_element_id_cover', 'gal_dir');
        $this->dropForeignKey('fk_gal_element_dir_id', 'gal_element');
        $this->dropTable('gal_right');
        $this->dropTable('gal_group_user');
        $this->dropTable('gal_group');
        $this->dropTable('gal_dir');
        $this->dropTable('gal_element');
        $this->dropTable('tbl_user_field');
        $this->dropTable('tbl_user_authenticate');
        $this->dropTable('tbl_user');
        $this->dropTable('tbl_auth_assignment');
        $this->dropTable('tbl_auth_item_child');
        $this->dropTable('tbl_auth_item');
        return true;
    }
}




