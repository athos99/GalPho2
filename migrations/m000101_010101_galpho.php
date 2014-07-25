<?php

use yii\db\Schema;


class m000101_010101_galpho extends yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
                'id' => 'pk',
                'username' => 'VARCHAR(64) NULL',


                'email' => 'VARCHAR(100) NULL',
                'validated' => 'boolean DEFAULT 0',
                'active' => 'boolean DEFAULT 0',
                'superuser' => 'boolean DEFAULT 0',
                'create' => 'datetime',
                'last_login' => 'datetime',

                'auth_key' => Schema::TYPE_STRING . '(32) ',
                'password_hash' => Schema::TYPE_STRING . ' ',
                'password_reset_token' => Schema::TYPE_STRING,
                'role' => Schema::TYPE_SMALLINT . ' DEFAULT 10',
                'status' => Schema::TYPE_SMALLINT . ' DEFAULT 10',
                'created_at' => Schema::TYPE_INTEGER . ' ',
                'updated_at' => Schema::TYPE_INTEGER . ' ',
            ],
            $tableOptions);
        $this->insert('{{%user}}', [
            'id' => 1,
            'username' => 'admin',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'auth_key' => Yii::$app->getSecurity()->generateRandomKey(),
            'active' => 1,
            'validated' => 1,
            'superuser' => 1,
            'create' => new \yii\db\Expression('NOW()'),
        ]);


        $this->createTable('{{%user_authenticate}}', [
                'id' => 'pk',
                'user_id' => 'integer NOT NULL',
                'provider' => 'VARCHAR(64) NULL',
                'identifier' => 'VARCHAR(256) NULL',
                'authenticate' => 'VARCHAR(512) NULL',
                'user_data' => 'text',
                'expire' => 'datetime NULL',
                'active' => 'boolean DEFAULT 0',
            ],
            $tableOptions);
        $this->createIndex('i_user_id', '{{%user_authenticate}}', 'user_id', false);
        $this->addForeignKey('fk_user_authenticate_user_id', '{{%user_authenticate}}',
            'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');


        $this->insert('{{%user_authenticate}}', [
            'id' => 1,
            'user_id' => 1,
            'provider' => 'google',
            'identifier' => 'https://www.google.com/accounts/o8/id?id=AItOawmjBilRbLs6W_kFHUl_9DGEmfXRKAe369s',
            'expire' => new \yii\db\Expression('TIMESTAMPADD(WEEK, 4,NOW())'),
            'active' => '1',
        ]);

        $this->createTable('{{%user_field}}', [
                'id' => 'pk',
                'user_id' => 'integer NOT NULL',
                'field' => 'VARCHAR(64) NOT NULL',
                'value' => 'text',
            ],
            $tableOptions);
        $this->addForeignKey('fk_user_field_user_id', '{{%user_field}}', 'user_id',
            '{{%user}}', 'id', 'CASCADE', 'CASCADE');


        $this->createTable('{{%auth_item}}', [
                'name' => 'VARCHAR(64) NOT NULL PRIMARY KEY',
                'type' => 'integer NOT NULL',
                'description' => 'text',
                'biz_rule' => 'text',
                'data' => 'text'
            ],
            $tableOptions);
        $this->createIndex('i_type', '{{%auth_item}}', 'type', false);


        $this->createTable('{{%auth_item_child}}', [
                'parent' => 'VARCHAR(64) NOT NULL',
                'child' => 'VARCHAR(64) NOT NULL',
            ],
            $tableOptions);
        $this->execute('ALTER TABLE {{%auth_item_child}} ADD PRIMARY KEY ( `parent` , `child` )');
        $this->addForeignKey('fk_parent', '{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_child', '{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');


        $this->createTable('{{%auth_assignment}}', [
                'item_name' => 'VARCHAR(64) NOT NULL',
                'user_id' => 'VARCHAR(64) NOT NULL PRIMARY KEY',
                'biz_rule' => 'text',
                'data' => 'text'],
            $tableOptions);
        $this->addForeignKey('fk_item_name', '{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');


        $this->createTable('{{%gal_dir}}', [
                'id' => 'pk',
                'element_id_cover' => 'integer',
                'path' => 'text',
                'title' => 'VARCHAR(256) NULL',
                'description' => 'text NULL',
                'create_time' => 'datetime NULL',
                'update_time' => 'datetime NULL',
                'sort_order' => 'VARCHAR(30) NULL',
            ],
            $tableOptions);
        $this->createIndex('i_path', '{{%gal_dir}}', 'path(255)', true);

        $this->insert('{{%gal_dir}}', [
            'id' => 1,
            'element_id_cover' => null,
            'path' => '',
            'title' => 'root',
            'create_time' => new \yii\db\Expression('NOW()'),
            'update_time' => new \yii\db\Expression('NOW()')
        ]);

        $this->createTable('{{%gal_element}}', [
                'id' => 'pk',
                'dir_id' => 'integer NOT NULL',
                'name' => 'VARCHAR(128) NULL',
                'title' => 'VARCHAR(256) NULL',
                'description' => 'text NULL',
                'create_time' => 'datetime NULL',
                'update_time' => 'datetime NULL',
                'format' => 'VARCHAR(10) NOT NULL',
                'rank' => 'integer DEFAULT 0'
            ],
            $tableOptions);


        //       $this->addForeignKey('fk_gal_dir_element_id_cover', '{{%gal_dir}}',
        //           'element_id_cover', '{{%gal_element}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_gal_element_dir_id', '{{%gal_element}}',
            'dir_id', '{{%gal_dir}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%gal_group}}', [
                'id' => 'pk',
                'permanent' => 'boolean default 0',
                'name' => 'VARCHAR(128) NULL',
                'description' => 'text NULL'
            ],
            $tableOptions);


        $this->insert('{{%gal_group}}', [
            'id' => 1,
            'permanent' => 1,
            'name' => 'anonymous',
            'description' => 'unauthenticated users',
        ]);


        $this->createTable('{{%gal_group_user}}', [
                'group_id' => 'integer',
                'user_id' => 'integer',
            ],
            $tableOptions);
        $this->addForeignKey('fk_gal_group_user_group_id', '{{%gal_group_user}}',
            'group_id', '{{%gal_group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_gal_group_user_user_id', '{{%gal_group_user}}',
            'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%gal_right}}', [
                'group_id' => 'integer',
                'dir_id' => 'integer',
                'value' => 'integer NOT NULL DEFAULT 0'
            ],
            $tableOptions);
        $this->addPrimaryKey('pk_primary', '{{%gal_right}}', 'group_id, dir_id');
        $this->addForeignKey('fk_gal_right_group_id', '{{%gal_right}}',
            'group_id', '{{%gal_group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_gal_right_dir_id', '{{%gal_right}}',
            'dir_id', '{{%gal_dir}}', 'id', 'CASCADE', 'CASCADE');


        $this->insert('{{%gal_right}}', [
            'group_id' => 1,
            'dir_id' => 1,
            'value' => 0xFF,
        ]);


        return true;
    }

    public function down()
    {
        //     $this->dropForeignKey('fk_gal_dir_element_id_cover', '{{%gal_dir}}');
        $this->dropForeignKey('fk_gal_element_dir_id', '{{%gal_element}}');
        $this->dropTable('{{%gal_right}}');
        $this->dropTable('{{%gal_group_user}}');
        $this->dropTable('{{%gal_group}}');
        $this->dropTable('{{%gal_dir}}');
        $this->dropTable('{{%gal_element}}');
        $this->dropTable('{{%user_field}}');
        $this->dropTable('{{%user_authenticate}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%auth_assignment}}');
        $this->dropTable('{{%auth_item_child}}');
        $this->dropTable('{{%auth_item}}');
        return true;
    }
}




