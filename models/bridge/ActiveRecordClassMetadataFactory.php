<?php

namespace app\models\bridge;

use JMS\Serializer\Metadata\ClassMetadata;
use Metadata\MetadataFactoryInterface;
use app\models\bridge\ActiveRecordPropertyMetadataFactory as PropertyMetadataFactory;

class ActiveRecordClassMetadataFactory implements MetadataFactoryInterface
{

    private $propertyMetadataFactory;

    private $schemaProvider;

    public function __construct(PropertyMetadataFactory $propertyMetadataFactory, ClassSchemaProvider $schemaProvider)
    {
        $this->propertyMetadataFactory = $propertyMetadataFactory;
        $this->schemaProvider = $schemaProvider;
    }

    public function getMetadataForClass($className)
    {
        if ($className instanceof \ReflectionClass) {
            $reflection = $className;
            $className = $className->getName();
        }
        else {
            $reflection = new \ReflectionClass($className);
        }
        if (false === $reflection->isSubclassOf('yii\\db\\ActiveRecord')) {
            // Only supports ActiveRecords
            return;
        }
        $schema = $this->schemaProvider->getSchemaForClass($className);
        $metadata = new ClassMetadata($className);
        foreach($schema->getColumnNames() as $columnName) {
            $propertyMetadata = $this->propertyMetadataFactory->getMetadataForProperty($columnName, $className);
            $metadata->addPropertyMetadata($propertyMetadata);
        }
        return $metadata;
    }


}