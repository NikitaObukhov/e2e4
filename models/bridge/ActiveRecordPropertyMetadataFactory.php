<?php

namespace app\models\bridge;

class ActiveRecordPropertyMetadataFactory
{

    private $caster;
    
    private $schemaProvider;

    public function __construct(DBTypeToJMSCaster $caster, ClassSchemaProvider $schemaProvider)
    {
        $this->caster = $caster;
        $this->schemaProvider = $schemaProvider;
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
}