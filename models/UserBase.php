<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property integer $validated
 * @property integer $active
 * @property integer $superuser
 * @property string $create
 * @property string $last_login
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property GalGroupUser[] $galGroupUsers
 * @property UserAuthenticate[] $userAuthenticates
 * @property UserField[] $userFields
 */
class UserBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['validated', 'active', 'superuser', 'role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['create', 'last_login'], 'safe'],
            [['username'], 'string', 'max' => 64],
            [['email'], 'string', 'max' => 100],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'validated' => 'Validated',
            'active' => 'Active',
            'superuser' => 'Superuser',
            'create' => 'Create',
            'last_login' => 'Last Login',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'role' => 'Role',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalGroupUsers()
    {
        return $this->hasMany(GalGroupUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuthenticates()
    {
        return $this->hasMany(UserAuthenticate::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFields()
    {
        return $this->hasMany(UserField::className(), ['user_id' => 'id']);
    }
}
