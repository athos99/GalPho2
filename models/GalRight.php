<?php

namespace app\models;

use Yii;

class GalRight extends GalRightBase
{


    public function behaviors()
    {
        return [
            'ActiveRecordDependency' => [
                'class' => 'app\models\ActiveRecordDependency'
            ],
        ];
    }
}
