<?php

namespace app\tests\unit\models;

use yii\codeception\DbTestCase;
use app\tests\unit\fixtures;

class SignupFormTest extends DbTestCase
{

    use \Codeception\Specify;

    public function testCorrectSignup()
    {
        $model = $this->getMock('app\models\SignupForm', ['validate']);
        $model->expects($this->once())->method('validate')->will($this->returnValue(true));

        $model->username = 'some_username';
        $model->email = 'some_email@example.com';
        $model->password = 'some_password';

        $user = $model->signup();
        $this->assertInstanceOf('app\models\User', $user);
        expect('username should be correct', $user->username)->equals('some_username');
        expect('email should be correct', $user->email)->equals('some_email@example.com');
        expect('password should be correct', $user->validatePassword('some_password'))->true();
    }

    public function testNotCorrectSignup()
    {
        $model = $this->getMock('app\models\SignupForm', ['validate']);
        $model->expects($this->once())->method('validate')->will($this->returnValue(false));

        expect('user should not be created', $model->signup())->null();
    }

    public function fixtures()
    {
        return [
            'user' => fixtures\UserFixture::className()
        ];
    }
}
