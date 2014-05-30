<?php
namespace app\models;

use app\extensions\yii\DbTableDependency;
use yii;
use yii\base;
use yii\caching;

class Galpho extends base\Object
{


    public static function getCacheStructure(array $groups)
    {
        asort($groups);
        $id = 'structure' . serialize($groups);
        $cache = Yii::$app->cacheFast;
        if (($value = $cache->get($id)) === false) {
            $value = self::getStructure($groups);
            $cache->set($id, $value, 0, new caching\ChainedDependency(array('dependencies'=>array(
                new DbTableDependency(GalDir::tableName()),
                new DbTableDependency(GalRight::tableName())))));
        }
        return $value;
    }




    public static function getStructure($idGroups = null)
    {
        $structure = array();

        $dirRights = GalDir::getElementsRightsForGroups($idGroups);

        foreach ($dirRights as $id => $dirRight) {
            $data = & $structure;
            $record = $dirRight[0];
            $level = 0;
            if (($path = trim($record->path, '/')) != '') {
                foreach (explode('/', $path) as $key) {
                    if (!array_key_exists($key, $data)) {
                        $data[$key] = array();
                    }
                    $data = & $data[$key];
                    $level++;
                }
            } else {
                $key = '';
            }

            $value = 0;
            if ($idGroups !== null) {
                foreach ($dirRight as $right) {
                    $value |= $right->value;
                }
            }
            $cover = isset( $dirRights[$record->dir_id][0]->path) ? $dirRights[$record->dir_id][0]->path.'/'.$record->name : null;
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





    public static  function getCacheElementsForDir( $idDir) {
        return GalElement::find()->where(array('dir_id'=>$idDir))->indexBy('name')->all();
    }

    public static  function getElementsForDir( $idDir) {
        return GalElement::find()->where(array('dir_id'=>$idDir))->all();
    }
}