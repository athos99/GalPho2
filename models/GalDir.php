<?php
namespace app\models;
use yii;
use yii\db\Query;
use app\galpho\MultiLingualTrait;
//use app\galpho\MultiLingualQuery;


class GalDir extends GalDirBase
{
    use MultiLingualTrait;

    public static $langForeignKey = 'dir_id';
    public static $langLanguages = [];
    public static $langAttributes = ['title', 'description'];

    public $_url = null;

    public function getUrl()
    {
        if ($this->_url === null) {
            $this->_url = rtrim(basename($this->path),'/');
        }
        return $this->_url;
    }


    public function setUrl($val)
    {
        $this->_url = rtrim($val,'/');
    }


    public function behaviors()
    {
        return [
            'activeRecordDependency' => [
                'class' => 'app\models\ActiveRecordDependency'
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
                'value' => new yii\db\Expression('NOW()'),
            ],
        ];
    }




    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['title'], 'required', 'on' => 'form'];
        $rules[] = [['title'], 'string', 'min' => 2];
        $rules[] = [['title','description'],'trim'];
        $rules[] = [['url'], 'match', 'pattern' => '/[^a-z0-9=_—–-]/', 'not' => true,
            'message' => Yii::t('app', 'Only lowercase alphanumeric chars')];
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


    public static function renameDir($path, $newPath)
    {
        $dirs = GalDir::find()->where('path like :path', ['path' => $path . '%'])->all();
        $pathSize = strlen($path);
        foreach ($dirs as $dir) {
            $dir->path = $newPath . substr($dir->path, $pathSize);
            $dir->save();
        }
    }


    /**
     * Save the rights of each group to the dir
     * and optionally to children dir
     *
     * @param $groupRights array of right for each group
     * @param $children boolean (apply right to children dir)
     */
    function saveRight( $groupRights, $children = false) {

        if ( $children) {
            $dirs = GalDir::find()->where('path like :path', ['path' => $this->path . '%'])->all();
        } else {
            $dirs = [$this];
        }
        foreach( $dirs as $dir) {
            GalRight::deleteAll(['dir_id'=>$dir->id]);
            foreach( $groupRights as $groupId=>$value) {
                $galRight = new GalRight();
                $galRight->group_id = $groupId;
                $galRight->dir_id= $dir->id;
                $galRight->value= $value;
                $galRight->save();
            }
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
    public static function getDirsRightsForGroups($idGroups = null, $language=null)
    {
         if ( $language === null) {
             $language = static::currentLanguage();
         }
        $query = new  Query();
        $subQuery = (new Query)->select('COUNT(*)')->where('esub.dir_id = t.id')->from('{{%gal_element}} esub');
        if ($idGroups !== null) {
            $query->select(['t.id', 't.*', 'r.value', 'e.dir_id', 'e.name', 'l_title' => 'l.title', 'l_description'=>'l.description', 'nb_element'=>$subQuery])
                ->from('{{%gal_dir}} t')
                ->leftJoin('{{%gal_dir_lang}} l', ['and', 't.id=l.dir_id', ['language' =>  $language]])
                ->leftJoin('{{%gal_right}} r', ['and', 't.id=r.dir_id', ['group_id' => $idGroups]])
                ->leftJoin('{{%gal_element}} e', 'e.id=t.element_id_cover');
        } else {
            $query->select(['t.id', 't.*', 'e.dir_id', 'e.name', 'l_title'=>'l.title', 'l_description'=>'l.description', 'nb_element'=>$subQuery])
                ->from('{{%gal_dir}} t')
                ->leftJoin('{{%gal_dir_lang}} l', ['and', 't.id=l.dir_id', ['language' =>  $language]])
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
