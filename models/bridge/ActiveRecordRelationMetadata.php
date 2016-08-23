<?php

namespace app\models\bridge;



use JMS\Serializer\Exclusion\GroupsExclusionStrategy;
use JMS\Serializer\Metadata\PropertyMetadata;
use yii\helpers\BaseInflector;

class ActiveRecordRelationMetadata extends PropertyMetadata
{

    public $class;

    public $name;
    
    public $tableName;

    public $propertyPath;

    public $groups;


    public function __construct($class, $tableName)
    {
        $this->class = $class;
        $this->name = $tableName;
        $this->tableName = $tableName;
        $this->propertyPath = lcfirst(BaseInflector::id2camel($this->tableName, '_'));
        $this->groups = [$tableName];
    }

    /**
     * @param object $obj
     *
     * @return mixed
     */
    public function getValue($obj)
    {
        return $obj->{$this->propertyPath};
    }

    /**
     * @param object $obj
     * @param string $value
     */
    public function setValue($obj, $value)
    {
        $this->{$this->name} = $value;
    }

}