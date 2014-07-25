<?php

namespace app\models;

use Yii;

class GalElement extends GalElementBase
{
    public function behaviors()
    {
        return [
            'ActiveRecordDependency' => [
                'class' => 'app\models\ActiveRecordDependency',
            ],
        ];
    }

}
