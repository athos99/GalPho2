<?php
namespace app\tests\unit\models;

use app\models\GalDir;
use app\models\GalDirBase;
use app\models\Galpho;
use app\models\DbTableDependency;
use yii\codeception\DbTestCase;
use \tests\unit\fixtures;

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
    }

    /**
     * Test function GetStructure
     *
     *  check result in function of different id groups value
     *
     */
    public function test_GetStructure() {
        $struct = Galpho::getStructure([1]);
        $this->assertNotEmpty($struct);
        $this->assertInternalType('array',$struct);
        $this->assertEquals($struct['#']['id'], $this->galElements['element1']['id']);
        $this->assertEquals($struct['#']['right'], $this->galRights['i11']['value']);
        $this->assertEquals($struct['#']['cover'], $this->galElements['element1']['name']);
        $this->assertEquals($struct['#']['description'], $this->galDirs['dir1']['description']);
        $this->assertEquals($struct['#']['level'], 0);
        $this->assertEquals($struct['#']['name'], '');
        $this->assertEquals($struct['#']['path'], '');
        $this->assertEquals($struct['#']['title'], $this->galDirs['dir1']['title']);


        $this->assertEquals($struct['dir2']['#']['id'], $this->galElements['element2']['id']);
        $this->assertEquals($struct['dir2']['#']['right'], $this->galRights['i21']['value']);
        $this->assertEquals($struct['dir2']['#']['cover'], 'dir2/'.$this->galElements['element2']['name']);
        $this->assertEquals($struct['dir2']['#']['description'], $this->galDirs['dir2']['description']);
        $this->assertEquals($struct['dir2']['#']['level'], 1);
        $this->assertEquals($struct['dir2']['#']['name'], 'dir2');
        $this->assertEquals($struct['dir2']['#']['path'], $this->galDirs['dir2']['path']);
        $this->assertEquals($struct['dir2']['#']['title'], $this->galDirs['dir2']['title']);


        $struct = Galpho::getCacheStructure([1,2]);
        $this->assertEquals($struct['#']['right'], $this->galRights['i11']['value'] | $this->galRights['i12']['value']);
        $this->assertEquals($struct['dir2']['#']['right'], $this->galRights['i21']['value']);


        $struct = Galpho::getCacheStructure([3]);
        $this->assertEquals($struct['#']['right'], $this->galRights['i13']['value']);
        $this->assertEquals($struct['dir2']['#']['right'],  $this->galRights['i22']['value']);

        $struct = Galpho::getCacheStructure([]);
        $this->assertEquals($struct['#']['right'], 0);
        $this->assertEquals($struct['dir2']['#']['right'], 0);

    }
    public function test_GalphoGetCacheStructure() {
        $txt1 = "ZZZZaaaaaaZZZZZZZZaaaaa";
        $txt2 = "XXXXzzzzzzXXXXXXzXZXZXZXZX";
        DbTableDependency::reset();
        $struct = Galpho::getCacheStructure([1]);
        $this->assertEquals($struct['#']['right'], $this->galRights['i11']['value']);

        // we change a record of table dir with model GalDirBase
        $rec = GalDirBase::find()->Where(['id'=>1])->one();
        $rec->description = $txt1;
        $rec->save();
        // the cache is not refresh, keep the old value
        $struct = Galpho::getCacheStructure([1]);
        $this->assertEquals($struct['#']['description'], $this->galDirs['dir1']['description']);

        // clear cache
        DbTableDependency::reset();
        // cache value is update
        $struct = Galpho::getCacheStructure([1]);
        $this->assertEquals($struct['#']['description'], $txt1);

        // we change a record of table dir with model GalDir, a dependency cache mechanism is implemented
        $rec = GalDir::find()->Where(['id'=>1])->one();
        $rec->description = $txt2;
        $rec->save();
        // cache value is update
        $struct = Galpho::getCacheStructure([1]);
        $this->assertEquals($struct['#']['description'], $txt2);

    }


    public function test_FindPath() {
        $struct = Galpho::getStructure([1,2,3]);
        $this->assertNotEmpty($struct);
        $this->assertInternalType('array',$struct);

        $dir = Galpho::findPath($struct, '');
        $this->assertEquals($dir['#']['path'], '');
        $dir = Galpho::findPath($struct, 'dir2');
        $this->assertEquals($dir['#']['path'], 'dir2');
        $dir = Galpho::findPath($struct, 'dir2/dir3');
        $this->assertEquals($dir['#']['path'], 'dir2/dir3');

    }
}