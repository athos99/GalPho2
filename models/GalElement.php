<?php
namespace app\models;
use yii;

class GalElement extends GalElementBase
{
    public function behaviors()
    {
        return [
            'activeRecordDependency' => [
                'class' => 'app\models\ActiveRecordDependency',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
                'value' => new yii\db\Expression('NOW()'),
            ],
        ];
    }

}
