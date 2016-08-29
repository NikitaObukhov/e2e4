<?php

namespace app\models\bridge;

use yii\db\Schema;

class ClassSchemaProvider
{

    private $dbPrefix;

    public function __construct($dbPrefix = null)
    {
        if (null === $dbPrefix) {
            $dbPrefix = \Yii::$app->db->tablePrefix;
        }
        $this->dbPrefix = $dbPrefix;
    }

    /**
     * @param $className
     * @return Schema
     */
    public function getSchemaForClass($className)
    {
        return call_user_func([$className, 'getTableSchema']);
    }

    public function getClassForeignKeys($className)
    {
        $schema = $this->getSchemaForClass($className);
        $foreignKeys = [];
        foreach($schema->foreignKeys as $foreignKey) {
            $tableName = array_shift($foreignKey);
            if ($this->dbPrefix) {
                $tableName = substr($tableName, strlen($this->dbPrefix));
            }
            $foreignKeys[$tableName] = $foreignKey;
        }
        return $foreignKeys;
    }
}