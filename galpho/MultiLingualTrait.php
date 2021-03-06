<?php
namespace app\galpho;

use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\validators\RequiredValidator;


trait MultiLingualTrait
{

//    public static $langForeignKey = 'dir_id';
//    public static $langAttributes = ['title', 'description'];
//    public static $languages = ['en'=>'english',fr'=>'french']
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
        $query = Yii::createObject(MultiLingualQuery::className(), [get_called_class()]);
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
        if ($this->language === 'all') {
            $subRows = [];
            foreach (static::$langAttributes as $attribute) {
                if (isset($this->$attribute)) {
                    foreach ($this->$attribute as $lang => $att) {
                        $subRows[$lang][$attribute] = $att;
                    }
                }
                $this->$attribute = isset($subRows[static::defaultLanguage()][$attribute]) ? $subRows[static::defaultLanguage()][$attribute] : '';
            }
            unset($subRows[static::defaultLanguage()]);
            parent::save(false, $attributeNames);
            $where = [static::$langForeignKey => $this->getPrimaryKey()];
            $this->db->createCommand()->delete(static::tableLangName(), $where)->execute();
            foreach ($subRows as $lang => $subRow) {
                $columns = $subRow;
                $columns[static::$langLanguage] = $lang;
                $columns[static::$langForeignKey] = $this->getPrimaryKey();
                $this->db->createCommand()->insert(static::tableLangName(), $columns)->execute();

            }
            return true;
        } elseif ($this->language == static ::defaultLanguage()) {
            return parent::save($runValidation, $attributeNames);
        } else {
            foreach (static::$langAttributes as $attribute) {
                $columns[$attribute] = $this->$attribute;
                $this->$attribute = $this->getOldAttribute($attribute);
            }

            if (parent::save($runValidation, $attributeNames) === false) {
                return false;
            }
            $where = [static::$langForeignKey => $this->getPrimaryKey(), static::$langLanguage => $this->language];
            $query = new Query();
            $rows = $query->from(static::tableLangName())
                ->where($where)
                ->all();
            $columns[static::$langLanguage] = $this->language;
            $columns[static::$langForeignKey] = $this->getPrimaryKey();


            if (empty($rows)) {
                return ($this->db->createCommand()->insert(static::tableLangName(), $columns)->execute() > 0);
            } else {
                return ($this->db->createCommand()->update(static::tableLangName(), $columns, $where, [])->execute() > 0);
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

    public function createValidators()
    {
        $validators = parent::createValidators();
        if ($this->language === 'all') {
            foreach ($validators as $validator) {
                foreach ($validator->attributes as $index => $attribute) {
                    if (in_array($attribute, static::$langAttributes)) {
                        $first = true;
                        foreach (static::$langLanguages as $key => $language) {
                            if ($first || (get_class($validator) !== RequiredValidator::className())) {
                                $validator->attributes[] = $attribute . '_' . $key;
                            }
                            $first = false;
                        }
                        unset($validator->attributes[$index]);
                    }
                }
            }
        }
        return $validators;
    }


    public function __get($name)
    {
        if (($pos = strrpos($name, '_')) !== false) {
            $baseName = substr($name, 0, $pos);
            if ($this->hasAttribute($baseName)) {
                $attribute = $this->getAttribute($baseName);
                return ArrayHelper::getValue($attribute, substr($name, $pos + 1));
            }
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (($pos = strrpos($name, '_')) !== false) {
            $attributeName = substr($name, 0, $pos);
            if (($attribute = $this->getAttribute($attributeName)) !== null) {
                $attribute[substr($name, $pos + 1)] = $value;
                $this->setAttribute($attributeName, $attribute);
                return;
            }
        }
        parent::__set($name, $value);
    }


    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                } elseif (is_array($values[$name])) {
                    $attribute = [];
                    foreach ($values[$name] as $k => $v) {
                        $attribute[$k] = $v;
                    }
                    $this->$name = $attribute;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }

}
