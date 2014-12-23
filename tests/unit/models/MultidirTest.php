<?php
namespace tests\unit\models;

use app\models\Multidir;
use tests\unit\PHPunit;
use Yii;
use tests\fixtures;

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

        $table = Multidir::tableLangName();

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

        $rec = new Multidir();
        $rec->title = 'aaaaaa';
        $rec->save();
        $rec->language = 'it';
        $rec->save();
        $rec->title = 'bbbb';
        $rec->save();
        $rec->language = 'aa';
        $rec->save();
        $rec->delete();
        Multidir::deleteAll();
        $z = 1;
    }
}