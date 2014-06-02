<?php
namespace app\tests\unit\models;

use yii\codeception\DbTestCase;
use app\tests\unit\fixtures;

class GalElementTest extends DbTestCase
{
    public function fixtures()
    {
        return [
            'elements' => fixtures\GalElementFixture::className(),
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['user']);
    }


    public function testGalElementExist() {
        $a = $this->elements['element1'];
    }

    // ...test methods...
}