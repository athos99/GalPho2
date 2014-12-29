<?php
namespace app\galpho;

use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;

class MultilingualQuery extends ActiveQuery
{
    public $language;

    public function localized($language)
    {
        $this->language = $language;
        if ($language == 'all') {
            unset($this->params[':language']);
            unset($this->join[0]);
            $this->select = ['*'];

//            $class = $this->modelClass;
//            $primaryKey = $class::primaryKey();
//            $this->join[0] = [
//                'LEFT JOIN',
//                $class::tableLangName(),
//                $class::tableLangName() . '.' . $class::$langForeignKey . '=' . $class::tableName() . '.' . reset($primaryKey)];
            return $this;
        }
        return $this->addParams([':language' => $language]);
    }

    public function populate($rows)
    {
        $rows = parent::populate($rows);
        if ($this->language == 'all' && $rows !== null) {

            $class = $this->modelClass;
            foreach ($rows as &$row) {
                $row->language = 'all';
                $query = new Query();
                $subRows = $query->from($class::tableLangName())
                    ->where([$class::$langForeignKey => $row->id])
                    ->all();

                foreach ($class::$langAttributes as $attribute) {
                    $att = [$class::defaultLanguage() => $row->$attribute];
                    foreach ($subRows as $subRow) {
                        $att[$subRow['language']] = $subRow[$attribute];

                    }
                    $row->$attribute = $att;
                }
            }
        }
        return $rows;
    }


}

trait MultilingualTrait
{

//    public static $langForeignKey = 'dir_id';
//    public static $langAttributes = ['title', 'description'];
    public static $langLanguage = 'language';

    public $language;

    public static function defaultLanguage()
    {
        return substr(Yii::$app->sourceLanguage, 0, 2);
    }

    public static function currentLanguage()
    {
        return substr(Yii::$app->language, 0, 2);
    }


    public static function tableLangName()
    {
        $schema = static::getDb()->getSchema()->getTableSchema(static::tableName());
        return $schema->fullName . '_lang';
    }


    public static function find()
    {
        $primaryKey = static::primaryKey();
        /** @var ActiveQuery $query */
        $query = Yii::createObject(MultilingualQuery::className(), [get_called_class()]);
        $query->language = static::currentLanguage();
        $query->join[0] = [
            'LEFT JOIN',
            static::tableLangName(),
            static::tableLangName() . '.' . static::$langForeignKey . '=' . static::tableName() . '.' . reset($primaryKey) . ' AND ' . static::tableLangName() . '.' . static::$langLanguage . ' = :language'];
        $query->addParams([':language' => $query->language]);
        $query->select(static::tableName() . '.*');
        $query->addSelect(static::tableLangName() . '.' . static::$langLanguage);
        foreach (static::$langAttributes as $attribute) {
            $query->addSelect(static::tableLangName() . '.' . $attribute . ' as lang_' . $attribute);
        }
        return $query;
    }


    public static function populateRecord($record, $row)
    {
        if (!isset($row[static::$langLanguage])) {
            $row[static::$langLanguage] = static::defaultLanguage();
        } else {
            foreach (static::$langAttributes as $attribute) {
                $name = 'lang_' . $attribute;
                if (isset($row[$name])) {
                    $row[$attribute] = $row[$name];
                }
            }
        }
        parent::populateRecord($record, $row);
    }


    public function save($runValidation = true, $attributeNames = null)
    {
        if (empty($this->language)) {
            $this->language = static::currentLanguage();
        }
        if ( $this->langage === 'all') {


        }
        elseif ($this->language == static::defaultLanguage()) {
            parent::save($runValidation, $attributeNames);
        } else {
            foreach (static::$langAttributes as $attribute) {
                $columns[$attribute] = $this->$attribute;
                $this->$attribute = $this->getOldAttribute($attribute);
            }

            parent::save($runValidation, $attributeNames);
            $where = [static::$langForeignKey => $this->getPrimaryKey(), static::$langLanguage => $this->language];
            $query = new Query();
            $rows = $query->from(static::tableLangName())
                ->where($where)
                ->all();
            $columns[static::$langLanguage] = $this->language;
            $columns[static::$langForeignKey] = $this->getPrimaryKey();


            if (empty($rows)) {
                $this->db->createCommand()->insert(static::tableLangName(), $columns)->execute();
            } else {
                $this->db->createCommand()->update(static::tableLangName(), $columns, $where, [])->execute();
            }
        }
    }


    public static function deleteAll($condition = '', $params = [])
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = self::getDb()->getQueryBuilder();
        /** @var Command $command */
        $command = self::getDb()->createCommand();
        $sql = "DELETE FROM " . self::getDb()->quoteTableName(static::tableLangName());
        $subQuery = (new Query)->select(static::primaryKey())->from(static::tableName())->where($condition, $params);
        $p = [];
        $where = $queryBuilder->buildWhere([static::$langForeignKey => $subQuery], $p);
        $sql = $where === '' ? $sql : $sql . ' ' . $where;
        $command->setSql($sql)->bindValues($p);
        $command->execute();
        parent::deleteAll($condition, $params);
    }

}
