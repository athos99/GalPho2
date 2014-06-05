<?php
namespace app\tests\unit\fixtures;

use yii\test\ActiveFixture;

class GalGroupFixture extends ActiveFixture
{
    public $modelClass = 'app\models\GalGroup';
    public $dataFile = '@app/tests/unit/fixtures/data/gal_group.php';
}