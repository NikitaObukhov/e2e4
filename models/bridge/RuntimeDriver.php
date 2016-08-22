<?php

namespace app\models\bridge;

use Metadata\Driver\DriverInterface;

/**
 * Class RuntimeDriver
 * @package app\models\bridge
 */
class RuntimeDriver implements DriverInterface
{

    private $metadataFactory;

    public function __construct(ActiveRecordClassMetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return \Metadata\ClassMetadata
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        return $this->metadataFactory->getMetadataForClass($class);
    }
}