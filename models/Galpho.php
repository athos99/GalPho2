<?php
namespace app\models;

//use app\models\DbTableDependency;
use yii;
use yii\base;
use yii\caching;

class Galpho extends base\Object
{


    public static function getCacheStructure(array $idGroups)
    {
        asort($idGroups);
        $id = 'structure' . serialize($idGroups);
        $cache = Yii::$app->cacheFast;
        if (($value = $cache->get($id)) === false) {
            $value = self::getStructure($idGroups);
            $cache->set($id, $value, 0, new caching\ChainedDependency(array('dependencies' => array(
                new DbTableDependency(GalDir::tableName()),
                new DbTableDependency(GalRight::tableName())))));
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
        $structure = array();
        $dirRights = GalDir::getDirsRightsForGroups($idGroups);
        foreach ($dirRights as $id => $dirRight) {
            $data = & $structure;
            $record = $dirRight[0];
            $level = 0;
            $key = '';
            if (($path = trim($record->path, '/')) != '') {
                foreach (explode('/', $path) as $key) {
                    if (!array_key_exists($key, $data)) {
                        $data[$key] = array();
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
            $data['#'] = array(
                'cover' => $cover,
                'level' => $level,
                'id' => $id,
                'title' => $record->title,
                'description' => $record->description,
                'right' => $value,
                'path' => $record->path,
                'name' => $key,
            );
        }
        return $structure;
    }


    public static function findPath(&$structure, $path)
    {
        if (($path = trim($path, '/')) != '') {
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
        if ( $dirPath !='') {
            $dirPath  .=  '/';
        }
        $id = 'element' . $idDir;
        $cache = Yii::$app->cacheFast;
        if (($value = $cache->get($id)) === false) {
            $value = [];
            $galElements = GalElement::find()->where(array('dir_id' => $idDir))->indexBy('name')->all();
            foreach ($galElements as $galElement) {
                $value[$galElement->name] = array(
                    'id' => $galElement->id,
                    'title' => $galElement->title,
                    'path' => $dirPath .  $galElement->name,
                    'cover' => $dirPath . $galElement->name,
                    'description' => $galElement->description,
                    'createTime' => $galElement->create_time,
                    'type' => 'img');
            }
            $cache->set($id, $value, 0, new caching\ChainedDependency(
                array('dependencies' =>
                    array(
                        new DbTableDependency(GalElement::tableName()),
                        new DbTableDependency(GalDir::tableName())
                    ))));
        }
        return $value;
//        return GalElement::find()->where(array('dir_id' => $idDir))->indexBy('name')->all();
    }

    public static function getElementsForDir($idDir)
    {
        return GalElement::find()->where(array('dir_id' => $idDir))->all();
    }
}