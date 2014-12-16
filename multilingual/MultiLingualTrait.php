<?php

namespace app\multilingual;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

trait MultilingualTrait
{


    public static function find()
    {
        $x = Yii::createObject(ActiveQuery::className(), [get_called_class()]);
        return $x;
    }


    public function getTranslations()
    {
        $q = $this->hasManyLang();
        return $q;
    }

    public function hasManyLang()
    {
        /* @var $class ActiveRecordInterface */
        /* @var $query ActiveQuery */
        $query = ActiveRecord::find();
        $query->from = (['{{%gal_dir_lang}}']);
        $query->primaryModel = $this;
        $query->link = ['dir_id' => 'id'];
        $query->multiple = true;
        return $query;
    }


    /**
     * Declares a `has-many` relation.
     * The declaration is returned in terms of a relational [[ActiveQuery]] instance
     * through which the related record can be queried and retrieved back.
     *
     * A `has-many` relation means that there are multiple related records matching
     * the criteria set by this relation, e.g., a customer has many orders.
     *
     * For example, to declare the `orders` relation for `Customer` class, we can write
     * the following code in the `Customer` class:
     *
     * ~~~
     * public function getOrders()
     * {
     *     return $this->hasMany(Order::className(), ['customer_id' => 'id']);
     * }
     * ~~~
     *
     * Note that in the above, the 'customer_id' key in the `$link` parameter refers to
     * an attribute name in the related class `Order`, while the 'id' value refers to
     * an attribute name in the current AR class.
     *
     * Call methods declared in [[ActiveQuery]] to further customize the relation.
     *
     * @param string $class the class name of the related record
     * @param array $link the primary-foreign key constraint. The keys of the array refer to
     * the attributes of the record associated with the `$class` model, while the values of the
     * array refer to the corresponding attributes in **this** AR class.
     * @return ActiveQueryInterface the relational query object.
     */
    public function myhasMany($class, $link)
    {
        /* @var $class ActiveRecordInterface */
        /* @var $query ActiveQuery */
        $query = $class::find();
        $query->primaryModel = $this;
        $query->link = ['dir_id' => 'id'];
        $query->multiple = true;
        return $query;
    }

    /**
     * Relation to model translations
     * @return ActiveQuery
     */
    public function mygetTranslations()
    {
        return $this->owner->hasMany($this->langClassName, [$this->langForeignKey => $this->_ownerPrimaryKey]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalDirLangs()
    {
        return $this->hasMany(GalDirLang::className(), ['dir_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalElements()
    {
        return $this->hasMany(GalElement::className(), ['dir_id' => 'id']);

        return $this->hasMany(GalGroup::className(), ['id' => 'group_id'])->viaTable('{{%gal_right}}', ['dir_id' => 'id']);
    }


    /**
     * Relation to model translation
     * @param $language
     * @return ActiveQuery
     */
    public function getTranslation($language = null)
    {
        $language = $language ? $language : $this->_currentLanguage;
        return $this->owner->hasMany($this->langClassName, [$this->langForeignKey => $this->_ownerPrimaryKey])
            ->where([$this->languageField => $language]);
    }


}