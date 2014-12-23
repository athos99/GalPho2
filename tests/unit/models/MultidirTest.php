<?php
namespace tests\unit\models;

use tests\unit\PHPunit;
use Yii;
use tests\fixtures;
use yii\db\ActiveRecord;
use app\galpho\MultilingualTrait;
use app\galpho\MultilingualQuery;

class MultiDir extends ActiveRecord{
    use MultilingualTrait;

    public static $langForeignKey = 'dir_id';

    public static $langAttributes = ['title', 'description'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gal_dir}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
        ];
    }


}


class MultiDirTest extends PHPunit
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
        $this->assertEquals('g2t_gal_dir_lang', $table);
        $lang = Multidir::defaultLanguage();
        $this->assertEquals('fr', $lang);

        $rec = Multidir::find()->Where(['g2t_gal_dir.id' => '1']);
        $this->assertInstanceOf(MultilingualQuery::className(),$rec);
        $row = $rec->one();
        $this->assertNotEmpty($row);
        $this->assertInstanceOf(MultiDir::className(),$row);

        $this->assertEquals(null,$row->language);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['id'],$row->id);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['description'],$row->description);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['title'],$row->title);


        $rec = Multidir::find()->Where(['g2t_gal_dir.id' => '1']);
        $rows = $rec->all();
        $this->assertNotEmpty($rows);
        $this->assertInternalType('array',$rows);
        $row = $row[0];
        $this->assertInstanceOf(MultiDir::className(),$row);
        $this->assertEquals(null,$row->language);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['id'],$row->id);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['description'],$row->description);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['title'],$row->title);



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