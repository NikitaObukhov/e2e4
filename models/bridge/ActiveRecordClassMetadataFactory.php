<?php

namespace app\models\bridge;

use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\VirtualPropertyMetadata;
use Metadata\MetadataFactoryInterface;
use app\models\bridge\ActiveRecordPropertyMetadataFactory as PropertyMetadataFactory;
use Metadata\MethodMetadata;
use yii\di\Container;
use yii\web\Request;

class ActiveRecordClassMetadataFactory implements MetadataFactoryInterface
{

    private $propertyMetadataFactory;

    private $schemaProvider;

    public function __construct(PropertyMetadataFactory $propertyMetadataFactory, ClassSchemaProvider
    $schemaProvider)
    {

        $this->propertyMetadataFactory = $propertyMetadataFactory;
        $this->schemaProvider = $schemaProvider;
        $this->container = \Yii::$container;
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
        $virtualProperties = $this->propertyMetadataFactory->getExpandedVirtualProperties($className);
        foreach($virtualProperties as $virtualProperty) {
            $metadata->addPropertyMetadata($virtualProperty);
        }
        return $metadata;
    }

    /**
     * @return EventDispatcherInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function getEventDispatcher()
    {
        return $this->container->get('serializer.event_dispatcher');
    }


}