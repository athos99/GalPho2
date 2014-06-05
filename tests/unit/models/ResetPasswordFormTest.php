<?php

namespace frontend\tests\unit\models;

use yii\codeception\DbTestCase;
use app\tests\unit\fixtures;
use app\models\ResetPasswordForm;
use yii\test\Fixture;

class ResetPasswordFormTest extends DbTestCase
{

    use \Codeception\Specify;

    public function testResetPassword()
    {
        $this->specify('wrong reset token', function () {
            $this->setExpectedException('\Exception', 'Wrong password reset token.');
            new ResetPasswordForm('notexistingtoken_1391882543');
        });

        $this->specify('not correct token', function () {
            $this->setExpectedException('yii\base\InvalidParamException', 'Password reset token cannot be blank.');
            new ResetPasswordForm('');
        });
    }

    public function fixtures()
    {
        return [
            'user' =>fixtures\UserFixture::className()
        ];
    }
}
