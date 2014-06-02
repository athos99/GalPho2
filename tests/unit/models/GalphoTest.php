<?php
namespace app\tests\unit\models;

use app\models\Galpho;
use app\models\DbTableDependency;
use yii\codeception\DbTestCase;
use app\tests\unit\fixtures;

class GalphoTest extends DbTestCase
{
    public function fixtures()
    {
        return [
            'galElements' => fixtures\GalElementFixture::className(),
            'galDirs' => fixtures\GalDirFixture::className(),
            'galGroups' => fixtures\GalGroupFixture::className(),
            'galGroupUsers' => fixtures\GalGroupUserFixture::className(),
            'galRights' => fixtures\GalRightFixture::className(),
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['user']);
    }


    public function testGalpho() {
        DbTableDependency::reset();
        $struct = Galpho::getCacheStructure([1,2]);
        $struct = Galpho::getCacheStructure([]);
    }

}