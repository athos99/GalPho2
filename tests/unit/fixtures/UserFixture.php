<?php
namespace app\tests\unit\fixtures;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User';
    public $dataFile = '@app/tests/unit/fixtures/data/user.php';
}


