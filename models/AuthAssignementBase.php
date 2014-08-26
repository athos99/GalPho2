<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "g2_auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property string $biz_rule
 * @property string $data
 *
 * @property AuthItem $itemName
 */
class AuthAssignementBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['biz_rule', 'data'], 'string'],
            [['item_name', 'user_id'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('app', 'Item Name'),
            'user_id' => Yii::t('app', 'User ID'),
            'biz_rule' => Yii::t('app', 'Biz Rule'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }
}
