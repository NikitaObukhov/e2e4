<?php

namespace app\models\bridge;



use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Exclusion\GroupsExclusionStrategy;
use JMS\Serializer\Metadata\PropertyMetadata;
use yii\helpers\BaseInflector;

class ActiveRecordPropertyMetadata extends PropertyMetadata
{

    public $class;

    public $name;

    public $groups;


    public function __construct($class, $name)
    {
        $this->class = $class;
        $this->name = $name;
        $this->groups = [GroupsExclusionStrategy::DEFAULT_GROUP, $name];
    }

    /**
     * @param object $obj
     *
     * @return mixed
     */
    public function getValue($obj)
    {
        $explicitGetter = 'get'. BaseInflector::id2camel($this->name, '_');
        if (method_exists($obj, $explicitGetter)) {
            return call_user_func([$obj, $explicitGetter]);
        }
        return $obj->{$this->name};
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