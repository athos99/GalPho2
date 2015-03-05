<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "g2_user_field".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $field
 * @property string $value
 *
 * @property User $user
 */
class UserFieldBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_field}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'field'], 'required'],
            [['user_id'], 'integer'],
            [['value'], 'string'],
            [['field'], 'string', 'max' => 64]
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
            'field' => Yii::t('app', 'Field'),
            'value' => Yii::t('app', 'Value'),
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
