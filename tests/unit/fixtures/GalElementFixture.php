<?php
namespace app\tests\unit\fixtures;

use yii\test\ActiveFixture;

class GalElementFixture extends ActiveFixture
{
    public $modelClass = 'app\models\GalElement';
    public $dataFile = '@app/tests/unit/fixtures/data/gal_element.php';
}