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

class MultidirTest extends PHPunit
{
    public function fixtures()
    {
        return [
            'galDirs' => fixtures\GalDirFixture::className(),
            'galDirLangs' => fixtures\GalDirLangFixture::className(),
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
    public function test_Multidir()
    {

        $rec = Multidir::find()->Where(['g2t_gal_dir.id' => '1']);
        $rec = $rec->one();
        $rec = Multidir::find()->Where(['g2t_gal_dir.id' => '1']);
        $rec = $rec->localized('en');

        $rec = $rec->one();

        $rec = Multidir::find()->Where(['g2t_gal_dir.id' => '1']);
        $rec = $rec->localized('xx');

        $rec = $rec->one();
        $x = $rec->title;
        $rec->title = 'ggggg';
        $rec->save();
        $z = 1;
    }
}