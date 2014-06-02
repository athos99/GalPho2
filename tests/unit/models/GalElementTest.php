<?php
namespace app\tests\unit\models;

use app\models\GalElement;
use yii\codeception\DbTestCase;
use app\tests\unit\fixtures;

class GalElementTest extends DbTestCase
{
    public function fixtures()
    {
        return [
            'galElements' => fixtures\GalElementFixture::className(),
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['user']);
    }


    public function testGalElementExist() {
        $elementData = $this->galElements['element1'];
        $rec = GalElement::find()->Where(['id'=>$elementData['id']])->one();
        $this->assertNotEmpty($rec);

        $dir = $rec->getDir();
    }

}