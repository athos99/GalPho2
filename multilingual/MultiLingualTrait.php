<?php

namespace athos99\multilingual;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class MultilingualTrait extends ActiveRecord {

    /**
     * Relation to model translations
     * @return ActiveQuery
     */
    public function getTranslations()
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