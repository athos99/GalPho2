<?php
namespace tests\unit\models;

use tests\unit\PHPunit;

use Yii;
use yii\codeception\DbTestCase;
use tests\fixtures;

use app\models\GalElement;


class GalElementTest extends PHPunit
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
    public function testGalElementRecord()
    {
        $elementData = $this->getFixture('galElements')['element1'];
        $elementDir = $this->getFixture('galDirs')['dir1'];

        // get 'element1', check the name
        $rec = GalElement::find()->Where(['id' => $elementData['id']])->one();
        $this->assertNotEmpty($rec);
        $this->assertEquals($elementData['name'], $rec->name);
        // get the relational dir records
        $dir = $rec->dir;
        $this->assertNotEmpty($rec);
        $this->assertEquals($elementDir['title'], $dir->title);

    }

}