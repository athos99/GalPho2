<?php
namespace app\models;

use Yii;
use yii\db\Query;


class GalDir extends GalDirBase
{
    public function behaviors()
    {
        return [
            'ActiveRecordDependency' => [
                'class' => 'app\models\ActiveRecordDependency'
            ],
        ];
    }


    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['title'], 'required', 'on' => 'form'];
        $rules[] = [['title'], 'string', 'min' => 2];
        return $rules;
    }


    public function getCoverElement()
    {
        return $this->hasOne('GalElement', ['id' => 'element_id_cover']);
    }


    /**
     * Return  GalRight list indexed by group_id
     * @return \yii\db\ActiveQuery
     */
    public function getIndexedRights()
    {
        return $this->hasMany('GalRight', ['dir_id' => 'id'])->indexBy('group_id');
    }


    /**
     * Return  Groups list indexed by group_id
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany('GalGroup', ['id' => 'group_id'])
            ->indexBy('id')
            ->viaTable('gal_right', ['dir_id' => 'id']);
    }



    public static function renameDir( $path, $newPath) {
        $dirs = GalDir::find()->where('path like :path',['path'=>$path.'%'])->all();
        $pathSize = strlen($path);
        foreach( $dirs as $dir) {
            $dir->path = $newPath . substr($dir->path, $pathSize) ;
            $dir->save();
        }
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
                ->from('{{%gal_dir}} t')
                ->leftJoin('{{%gal_right}} r', ['and', 't.id=r.dir_id', ['group_id' => $idGroups]])
                ->leftJoin('{{%gal_element}} e', 'e.id=t.element_id_cover');
        } else {
            $query->select('t.id, t.*, e.dir_id, e.name')
                ->from('{{%gal_dir}} t')
                ->leftJoin('{{%gal_element}} e', 'e.id=t.element_id_cover');

        }
        return $query->createCommand()->queryAll(\PDO::FETCH_OBJ | \PDO::FETCH_GROUP);
    }


    public static function withRightsForGroups($query, $idGroups)
    {
        return $query->with(['rights' => function ($query) use ($idGroups) {
                $query->andWhere(['group_id' => $idGroups]);
            }]);
    }
}
