<?php

namespace app\models;

use yii\caching\Dependency;


/**
 * DbTableDependency represents a dependency based on db table state
 *
 * DbTableDependency checks if a table record is changed, deleted, added or not.
 * If the table state is changed, the dependency is reported as changed.
 * To specify which table state this dependency should check with,
 * set to the name of the table name.
 *
 */
class DbTableDependency extends Dependency
{
    const KEY = 'yii::DbTableDependency';

    static protected $_tableState;
    /** @var  \yii\caching\Cache */
    static protected $_cache;
    static protected $_update = false;

    protected $_tableName;



    static public function reset() {
        self::$_cache = \Yii::$app->cacheFast;
        self::$_cache->flush();
        self::$_tableState = null;
    }
    /**
     * Returns a state table value.
     *
     */
    static public function getTableState($key = null)
    {
        if (self::$_tableState === null) {
            self::$_cache = \Yii::$app->cacheFast;
            self::$_tableState = self::$_cache->get(self::KEY);
        }
        if ($key !== null) {
            if (!isset(self::$_tableState[$key])) {
                self::changeTableState($key);
            }
            return self::$_tableState[$key];
        }

        return null;
    }

    /**
     * change the table state value by incrementation.

     */
    static public function changeTableState($key)
    {
        if (self::$_tableState === null) {
            self::getTableState();
        }
        if (empty(self::$_tableState)) {
            self::$_tableState = array($key => 1);
        } elseif (isset(self::$_tableState[$key])) {
            self::$_tableState[$key]++;
        } else {
            self::$_tableState[$key] = 1;
        }
        if (!self::$_update) {
            register_shutdown_function(array(__NAMESPACE__ . '\\DbTableDependency', 'close'));
            self::$_update = true;
        }
    }

    public static function close()
    {
        self::$_cache->set(self::KEY, self::$_tableState, 0, null);
    }

    /**
     * Constructor.
     * @param string $tabelName the name of the table name
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($tableName = null, $config = array())
    {
        $this->_tableName = $tableName;
        parent::__construct($config);
    }

    /**
     * Generates the data needed to determine if dependency has been changed.
     * This method returns the file's last modification time.
     * @return mixed the data needed to determine if dependency has been changed.
     */
    protected function generateDependencyData($cache)
    {
        if ($this->_tableName !== null) {
            return self::getTableState($this->_tableName);
        } else {
            return 0;
        }
    }
}
