<?php

////Assuming current language is english
//
//$model = Post::findOne(1);
//echo $model->title; //echo "English title"
//
////Now let's imagine current language is french
//$model = Post::findOne(1);
//echo $model->title; //echo "Titre en Français"
//
//$model = Post::find()->localized('en')->one();
//echo $model->title; //echo "English title"
//
////Current language is still french here

namespace app\multilingual;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class MultilingualQuery extends ActiveQuery
{
    public function localized($language = null)
    {
        if (!$language) {
            $language = Yii::$app->language;
        }

        return $this->andWhere(['language' => substr($language, 0, 2)]);
    }
}

trait MultilingualTrait
{

    public $langForeignKey = 'dir_id';
    public $langTableName = "{{%gal_dir_lang}}";
    public $langAttributes = ['title', 'description'];


    public static function find()
    {

        /** @var ActiveQuery $query */
        $query = Yii::createObject(MultilingualQuery::className(), [get_called_class()]);
        $query->leftJoin('{{%gal_dir_lang}}', '{{%gal_dir_lang}}.dir_id={{%gal_dir}}.id');
        $query->select('{{%gal_dir}}.*');
        $query->addSelect('{{%gal_dir_lang}}.*');
        return $query;
    }


    /**
     * Scope for querying by all languages
     * @return ActiveQuery
     */
    public function multilingual()
    {
        $this->with('translations');
        return $this;
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