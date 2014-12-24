<?php
namespace tests\unit\models;

use tests\unit\PHPunit;
use Yii;
use tests\fixtures;
use yii\db\ActiveRecord;
use app\galpho\MultilingualTrait;
use app\galpho\MultilingualQuery;


class MultiDir extends ActiveRecord
{
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


class MultiElem extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%gal_element}}';
    }

    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDir()
    {
        return $this->hasOne(MultiDir::className(), ['id' => 'dir_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirLocalized($lang)
    {
        return $this->hasOne(MultiDir::className(), ['id' => 'dir_id'])->localized($lang);
    }

}


class MultiDirTest extends PHPunit
{
    public function fixtures()
    {
        return [
            'galDirs' => fixtures\GalDirFixture::className(),
            'galDirLangs' => fixtures\GalDirLangFixture::className(),
            'galElements' => fixtures\GalElementFixture::className(),
        ];
    }

    protected function setUp()
    {
        parent::setUp();
    }


    /**
     * Test Multilingual extension
     *
     * The current language is French and default language is english
     *
     *
     */
    public function test_Multidir_Read()
    {
        // set current and default language
        Yii::$app->sourceLanguage = 'en-US';
        Yii::$app->language = 'fr-FR';

        // check the name of translate fields
        $table = Multidir::tableLangName();
        $this->assertEquals('g2t_gal_dir_lang', $table);

        /* check the language*/
        $defaultLang = Multidir::defaultLanguage();
        $this->assertEquals('en', $defaultLang);
        $currentLang = Multidir::currentLanguage();
        $this->assertEquals('fr', $currentLang);


        /* read with function one() record of type dir with id 1, normally it must be in french (user current language) */
        Yii::$app->language = 'fr-FR';
        $id = $this->getFixture('galDirs')['dir1']['id'];
        $aq = Multidir::find()->Where(['g2t_gal_dir.id' => $id]);
        $this->assertInstanceOf(MultilingualQuery::className(), $aq);
        $row = $aq->one();
        $this->assertNotEmpty($row);
        $this->assertInstanceOf(MultiDir::className(), $row);
        $this->assertEquals('fr', $row->language);
        $this->assertEquals($id, $row->id);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['path'], $row->path);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['description'], $row->description);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['title'], $row->title);


        /* read with function all() record of type dir with id 1, normally it must be in french (user current language) */
        Yii::$app->language = 'fr-FR';
        $id = $this->getFixture('galDirs')['dir1']['id'];
        $aq = Multidir::find()->Where(['g2t_gal_dir.id' => $id]);
        $rows = $aq->all();
        $this->assertNotEmpty($rows);
        $this->assertInternalType('array', $rows);
        $row = $rows[0];
        $this->assertNotEmpty($row);
        $this->assertInstanceOf(MultiDir::className(), $row);
        $this->assertEquals('fr', $row->language);
        $this->assertEquals($id, $row->id);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['path'], $row->path);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['description'], $row->description);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['title'], $row->title);


        /* Relation
            Read element id 1,
           After test relation getDir, to fetch dir with translation in french */
        Yii::$app->language = 'fr-FR';
        $id = $this->getFixture('galElements')['element1']['id'];
        $row = MultiElem::findOne($id);
        $dir = $row->dir;
        $this->assertEquals('fr', $dir->language);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['description'], $dir->description);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['title'], $dir->title);

        /* Relation
                   Read element id 1,
                  After test relation getDir, to fetch dir with translation in french */
        Yii::$app->language = 'fr-FR';
        $id = $this->getFixture('galElements')['element1']['id'];
        $row = MultiElem::findOne($id);
        $dir = $row->getDirLocalized('es')->one();
        $this->assertEquals('es', $dir->language);
        $this->assertEquals($this->getFixture('galDirLangs')['d1es']['description'], $dir->description);
        $this->assertEquals($this->getFixture('galDirLangs')['d1es']['title'], $dir->title);



        /* read dir for id 1 in default language */
        Yii::$app->language = 'en-US';
        $id = $this->getFixture('galDirs')['dir1']['id'];
        $aq = Multidir::find()->Where(['g2t_gal_dir.id' => $id]);
        $this->assertInstanceOf(MultilingualQuery::className(), $aq);
        $row = $aq->one();
        $this->assertNotEmpty($row);
        $this->assertInstanceOf(MultiDir::className(), $row);
        $this->assertEquals(null, $row->language); // beacuse we use the default language
        $this->assertEquals($id, $row->id);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['path'], $row->path);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['description'], $row->description);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['title'], $row->title);


        /* read dir for id 2 in default language, the translated record exist (this is not standard) !!! */
        Yii::$app->language = 'en-US';
        $id = $this->getFixture('galDirs')['dir2']['id'];
        $aq = Multidir::find()->Where(['g2t_gal_dir.id' => $id]);
        $this->assertInstanceOf(MultilingualQuery::className(), $aq);
        $row = $aq->one();
        $this->assertNotEmpty($row);
        $this->assertInstanceOf(MultiDir::className(), $row);
        $this->assertEquals('en', $row->language); // beacuse the translation exist
        $this->assertEquals($id, $row->id);
        $this->assertEquals($this->getFixture('galDirs')['dir2']['path'], $row->path);
        $this->assertEquals($this->getFixture('galDirLangs')['d2en']['description'], $row->description);
        $this->assertEquals($this->getFixture('galDirLangs')['d2en']['title'], $row->title);


        /* read dir for id 1 for a untranslated record language */
        Yii::$app->language = 'pt-PT';
        $id = $this->getFixture('galDirs')['dir1']['id'];
        $aq = Multidir::find()->Where(['g2t_gal_dir.id' => $id]);
        $this->assertInstanceOf(MultilingualQuery::className(), $aq);
        $row = $aq->one();
        $this->assertNotEmpty($row);
        $this->assertInstanceOf(MultiDir::className(), $row);
        $this->assertEquals(null, $row->language); // beacause we use the default language
        $this->assertEquals($id, $row->id);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['path'], $row->path);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['description'], $row->description);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['title'], $row->title);


        /* check localisation,
            user is in french, localize to en and read dir for id 1*/
        Yii::$app->language = 'fr-FR';
        $id = $this->getFixture('galDirs')['dir1']['id'];
        $aq = Multidir::find()->Where(['g2t_gal_dir.id' => $id]);
        $aq = $aq->localized('en');
        $this->assertEquals(null, $row->language); // beacause we use the default language
        $this->assertEquals($id, $row->id);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['path'], $row->path);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['description'], $row->description);
        $this->assertEquals($this->getFixture('galDirs')['dir1']['title'], $row->title);


        /* check localisation,
            user is in french, localize to en and read dir for id 1*/
        Yii::$app->language = 'fr-FR';
        $id = $this->getFixture('galDirs')['dir1']['id'];
        $aq = Multidir::find()->Where(['g2t_gal_dir.id' => $id]);
        $aq = $aq->localized('fr');
        $this->assertEquals($id, $row->id);
        $this->assertEquals('fr', $dir->language);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['description'], $dir->description);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['title'], $dir->title);


        /**
         * findOne
         *
         * user is french, read dir id:1, result in french
         */
        Yii::$app->language = 'fr-FR';
        $id = $this->getFixture('galDirs')['dir1']['id'];
        $row = MultiDir::findOne($id);
        $this->assertEquals($id, $row->id);
        $this->assertEquals('fr', $dir->language);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['description'], $dir->description);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['title'], $dir->title);


        /*
         * find all
         *
         * user is frech, read dir id:1, result in french
         */

        Yii::$app->language = 'fr-FR';
        $id = $this->getFixture('galDirs')['dir1']['id'];
        $rows = MultiDir::findAll($id);
        $row = $rows[0];
        $this->assertEquals($id, $row->id);
        $this->assertEquals('fr', $dir->language);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['description'], $dir->description);
        $this->assertEquals($this->getFixture('galDirLangs')['d1fr']['title'], $dir->title);
    }

    public function test_Multidir_Write()
    {


        $aq = Multidir::find()->Where(['g2t_gal_dir.id' => '1']);
        $aq = $aq->localized('xx');

        $aq = $aq->one();
        $x = $aq->title;
        $aq->title = 'ggggg';
        $aq->save();

        $aq = new Multidir();
        $aq->title = 'aaaaaa';
        $aq->save();
        $aq->language = 'it';
        $aq->save();
        $aq->title = 'bbbb';
        $aq->save();
        $aq->language = 'aa';
        $aq->save();
        $aq->delete();
        Multidir::deleteAll();
        $z = 1;
    }
}