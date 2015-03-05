<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "g2_user_authenticate".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property string $identifier
 * @property string $authenticate
 * @property string $user_data
 * @property string $expire
 * @property integer $active
 *
 * @property User $user
 */
class UserAuthenticateBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_authenticate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'active'], 'integer'],
            [['user_data'], 'string'],
            [['expire'], 'safe'],
            [['provider'], 'string', 'max' => 64],
            [['identifier'], 'string', 'max' => 256],
            [['authenticate'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'provider' => Yii::t('app', 'Provider'),
            'identifier' => Yii::t('app', 'Identifier'),
            'authenticate' => Yii::t('app', 'Authenticate'),
            'user_data' => Yii::t('app', 'User Data'),
            'expire' => Yii::t('app', 'Expire'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
