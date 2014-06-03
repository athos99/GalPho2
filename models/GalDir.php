<?php

namespace app\models;

use Yii;
use Yii\db\Query;


class GalDir extends GalDirBase
{
    public function behaviors()
    {
        return array(
            'ActiveRecordDependency' => array(
                'class' => 'app\models\ActiveRecordDependency',
            ),
        );
    }



    public function getCoverElement()
    {
        return $this->hasOne('GalElement', array('id' => 'element_id_cover'));
    }


    /**
     * Return  GalRight list indexed by group_id
     * @return \yii\db\ActiveQuery
     */
    public function getIndexedRights()
    {
        return $this->hasMany('GalRight', array('dir_id' => 'id'))->indexBy('group_id');
    }


    /**
     * Return  Groups list indexed by group_id
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany('GalGroup', array('id' => 'group_id'))
            ->indexBy('id')
            ->viaTable('gal_right', array('dir_id' => 'id'));
    }


    /**
     * Return the directory list
     * with the right value and cover element
     * in function of group id
     * grouped by dir id
     *
     * @param null $idGroups
     * @return array of array of object records
     */
    public static function getDirsRightsForGroups($idGroups = null)
    {
        $query = new  Query();

        if ($idGroups !== null) {
            $query->select('t.id, t.*, r.value, e.dir_id, e.name')
                ->from('gal_dir t')
                ->leftJoin('gal_right r', array('and', 't.id=r.dir_id', array('group_id' => $idGroups)))
                ->leftJoin('gal_element e', 'e.id=t.element_id_cover');
        } else {
            $query->select('t.id, t.*, e.dir_id, e.name')
                ->from('gal_dir t')
                ->leftJoin('gal_element e', 'e.id=t.element_id_cover');

        }
        return $query->createCommand()->queryAll(\PDO::FETCH_OBJ | \PDO::FETCH_GROUP);
    }


    public static function withRightsForGroups($query, $idGroups)
    {
        return $query->with(array('rights' => function ($query) use ($idGroups) {
                $query->andWhere(array('group_id' => $idGroups));
            }));
    }
}
