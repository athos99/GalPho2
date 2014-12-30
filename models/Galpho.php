<?php
namespace app\models;

//use app\models\DbTableDependency;
use yii;
use yii\base;
use yii\caching;
use yii\helpers\FileHelper;

class Galpho extends base\Object
{


    public static function getCacheStructure(array $idGroups)
    {
        asort($idGroups);
        $id = 'structure' . serialize($idGroups);
        $cache = Yii::$app->cacheFast;
        if (($value = $cache->get($id)) === false) {
            $value = self::getStructure($idGroups);
            $cache->set($id, $value, 0, new caching\ChainedDependency(['dependencies' => [
                new DbTableDependency(GalDir::tableName()),
                new DbTableDependency(GalRight::tableName())]]));
        }
        return $value;
    }


    /**
     *
     * For each directory and group appartenance, compute the right value
     *
     * @param null $idGroups
     * @return array
     */
    public static function getStructure(array $idGroups)
    {
        $structure = [];
        $dirRights = GalDir::getDirsRightsForGroups($idGroups);
        foreach ($dirRights as $id => $dirRight) {
            $data = & $structure;
            $record = $dirRight[0];
            $level = 0;
            $key = '';
            $path = isset($record->l_path) ? $record->l_path : $record->path;

            if (($path = trim($record->path, '/')) != '') {
                foreach (explode('/', $path) as $key) {
                    if (!array_key_exists($key, $data)) {
                        $data[$key] = [];
                    }
                    $data = & $data[$key];
                    $level++;
                }
            }

            $value = 0;
            foreach ($dirRight as $right) {
                $value |= $right->value;
            }
            if (isset($record->dir_id)) {
                $cover = ($dirRights[$record->dir_id][0]->path != '') ? $dirRights[$record->dir_id][0]->path . '/' . $record->name : $record->name;
            } else {
                $cover = null;
            }
            $data['#'] = [
                'cover' => $cover,
                'level' => $level,
                'id' => $id,
                'title' => $record->title,
                'description' => $record->description,
                'right' => $value,
                'path' => $record->path,
                'name' => $key
            ];
        }
        return $structure;
    }

    /**
     * @param $structure[]
     * @param $path string
     * @return false or structure[] of the path
     */

    public static function findPath(&$structure, $path)
    {
        if (($path = trim($path, '/')) != '' && ($path != '.')) {
            foreach (explode('/', $path) as $key) {
                if (!array_key_exists($key, $structure)) {
                    return false;
                }
                $structure = & $structure[$key];
            }
        }
        return $structure;
    }

    public static function getCacheListElementsForDir($idDir, $dirPath)
    {
        if ($dirPath != '') {
            $dirPath .= '/';
        }
        $id = 'element' . $idDir;
        $cache = Yii::$app->cacheFast;
        if (($value = $cache->get($id)) === false) {
            $value = [];
            $galElements = GalElement::find()->where(['dir_id' => $idDir])->indexBy('name')->all();
            foreach ($galElements as $galElement) {
                $value[$galElement->name] = [
                    'id' => $galElement->id,
                    'title' => $galElement->title,
                    'path' => $dirPath . $galElement->name,
                    'cover' => $dirPath . $galElement->name,
                    'description' => $galElement->description,
                    'info' => $galElement->info,
                    'createTime' => $galElement->created_at,
                    'type' => 'img'];
            }
            $cache->set($id, $value, 0, new caching\ChainedDependency(
                ['dependencies' =>
                    [
                        new DbTableDependency(GalElement::tableName()),
                        new DbTableDependency(GalDir::tableName())
                    ]]));
        }
        return $value;
//        return GalElement::find()->where(['dir_id' => $idDir)]->indexBy('name')->all();
    }

    public static function getElementsForDir($idDir)
    {
        return GalElement::find()->where(['dir_id' => $idDir])->all();
    }


    public static function clearCache()
    {
        DbTableDependency::reset();
        FileHelper::removeDirectory(Yii::getAlias('@runtime/cache') );
    }


}