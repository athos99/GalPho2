<?php
namespace app\galpho;

use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;

class MultiLingualQuery extends ActiveQuery
{
    public $language;

    public function localized($language)
    {
        $this->language = $language;
        if ($language == 'all') {
            unset($this->params[':language']);
            unset($this->join[0]);
            $this->select = ['*'];
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
