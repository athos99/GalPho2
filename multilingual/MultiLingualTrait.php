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
            $language = substr(Yii::$app->language,0, 2);
        }
        return $this->addParams([':language' => $language]);

    }
}

trait MultilingualTrait
{

    public $language;
    public static $langForeignKey = 'dir_id';
    public static $langTableName = "{{%gal_dir_lang}}";
    public static $langAttributes = ['title', 'description'];


    public static function find()
    {
        $language = substr(Yii::$app->language,0, 2);

        /** @var ActiveQuery $query */
        $query = Yii::createObject(MultilingualQuery::className(), [get_called_class()]);
        $query->join[0] = ['LEFT JOIN', '{{%gal_dir_lang}}',
            '{{%gal_dir_lang}}.dir_id={{%gal_dir}}.id AND {{%gal_dir_lang}}.language = :language'];
        $query->addParams([':language' => $language]);
        $query->select('{{%gal_dir}}.*');
        $query->addSelect('{{%gal_dir_lang}}.language');
        foreach (static::$langAttributes as $attribute) {
            $query->addSelect('{{%gal_dir_lang}}.' . $attribute . ' as lang_' . $attribute);
        }
        return $query;
    }


    public static function populateRecord($record, $row)
    {
        foreach (static::$langAttributes as $attribute) {
            $name = 'lang_' . $attribute;
            if (isset($row[$name])) {
                $row[$attribute] = $row[$name];
            }
        }
        parent::populateRecord($record, $row);
    }

}