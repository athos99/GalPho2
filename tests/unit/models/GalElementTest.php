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
            'galDirs' => fixtures\GalDirFixture::className(),

        ];
    }

    protected function setUp()
    {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['user']);
    }

    /**
     * Get one record form table gal_element and get relational record from table gal_dir
     */
    public function testGalElementRecord() {



        $elementData = $this->galElements['element1'];
        $elementDir = $this->galDirs['dir1'];

        // get 'element1', check the name
        $rec = GalElement::find()->Where(['id'=>$elementData['id']])->one();
        $this->assertNotEmpty($rec);
        $this->assertEquals($elementData['name'], $rec->name);
        // get the relational dir records
        $dir = $rec->dir;
        $this->assertNotEmpty($rec);
        $this->assertEquals($elementDir['title'], $dir->title);

    }

}