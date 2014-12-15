<?php
namespace tests\unit\models;
use app\models\Multidir;
use tests\unit\PHPunit;
use Yii;
use app\models\DbTableDependency;
use tests\fixtures;
use app\models\Galpho;
use app\models\GalDirBase;
use app\models\GalDir;

class GalphoTest extends PHPunit
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
    }

    /**
     * Test function GetStructure
     *
     *  check result in function of different id groups value
     *
     */
    public function test_Multidir() {

        $rec = Multidir::find()->Where(['id' => '1'])->one();

    }
}