<?php
namespace app\models;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class ActiveRecordDependency extends Behavior
{
    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return array(
            ActiveRecord::EVENT_BEFORE_INSERT => 'myBeforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'myBeforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'myABeforeDelete',
        );
    }

    public function myBeforeInsert($event)
    {
        DbTableDependency::changeTableState($this->owner->tableName());
    }
    public function myBeforeUpdate($event)
    {
        DbTableDependency::changeTableState($this->owner->tableName());
    }
    public function myBeforeDelete($event)
    {
        DbTableDependency::changeTableState($this->owner->tableName());
    }

}
