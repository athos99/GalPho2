<?php

use yii\db\Schema;

class m000101_010101_galpho extends \yii\db\Migration
{
    public function up()
    {
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
        return true;
    }
}




