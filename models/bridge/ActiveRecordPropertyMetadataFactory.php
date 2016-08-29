<?php

namespace app\models\bridge;

use yii\web\Request;

class ActiveRecordPropertyMetadataFactory
{

    private $caster;
    
    private $schemaProvider;

    private $request;

    public function __construct(DBTypeToJMSCaster $caster, ClassSchemaProvider $schemaProvider, Request $request)
    {
        $this->caster = $caster;
        $this->schemaProvider = $schemaProvider;
        $this->request = $request;
    }

    public function getMetadataForProperty($propertyName, $className)
    {
        $schema = $this->schemaProvider->getSchemaForClass($className);
        $foreignKeys = $this->schemaProvider->getClassForeignKeys($className);
        foreach($foreignKeys as $tableName => $foreignKey) {
            if (in_array($propertyName, $foreignKey)) {
                return new ActiveRecordRelationMetadata($className, $tableName);
            }
        }
        $propertyMetadata = new ActiveRecordPropertyMetadata($className, $propertyName);
        $column = $schema->getColumn($propertyName);
        if (null !== $type = $this->caster->cast($column->type)) {
            $propertyMetadata->setType($type);
        }
        $propertyMetadata->setAccessor(null, 'get'.$propertyName, 'set'.$propertyName);
        return $propertyMetadata;
    }

    public function getExpandedVirtualProperties($className)
    {
        $extra = $this->request->get('expand', '');
        $reflection = new \ReflectionClass($className);
        $properties = [];
        foreach(explode(',', $extra) as $field) {
            if ($reflection->hasMethod('get'.ucfirst($field))) {
                $properties[] =new ActiveRecordVirtualPropertyMetadata($className, $field);
            }
        }
        return $properties;
    }
}