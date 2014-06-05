<?php
namespace app\tests\unit\fixtures;

use yii\test\ActiveFixture;

class GalGroupUserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\GalGroupUser';
    public $dataFile = '@app/tests/unit/fixtures/data/gal_group_user.php';
}