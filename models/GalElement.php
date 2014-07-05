<?php

namespace app\models;

use Yii;

class GalElement extends GalElementBase
{
    public function behaviors()
    {
        return array(
            'ActiveRecordDependency' => array(
                'class' => 'app\models\ActiveRecordDependency',
            ),
        );
    }

}
