<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "g2_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property integer $permanent
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
 * @property string $created_at
 * @property string $updated_at
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
            [['permanent', 'validated', 'active', 'superuser', 'role', 'status'], 'integer'],
            [['create', 'last_login', 'created_at', 'updated_at'], 'safe'],
            [['username'], 'string', 'max' => 64],
            [['email'], 'string', 'max' => 100],
            [['auth_key', 'password_hash', 'password_reset_token'], 'string', 'max' => 140]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'permanent' => Yii::t('app', 'Permanent'),
            'validated' => Yii::t('app', 'Validated'),
            'active' => Yii::t('app', 'Active'),
            'superuser' => Yii::t('app', 'Superuser'),
            'create' => Yii::t('app', 'Create'),
            'last_login' => Yii::t('app', 'Last Login'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
