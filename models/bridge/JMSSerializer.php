<?php

namespace app\models\bridge;

use app\models\entity\SearchRequest;
use JMS\Serializer\SerializationContext;
use yii\rest\Serializer;
use \krtv\yii2\serializer\Serializer as KRTVSerializer;

class JMSSerializer extends Serializer
{

    private $serializer;

    private $format;

    private $serializationContext;

    public function __construct($config = [], $format = 'json', SerializationContext $serializationContext = null)
    {
        parent::__construct($config);
        $this->serializer = \Yii::$app->serializer;
        $this->format = $format;
        $this->serializationContext = $serializationContext;
    }

    public function init()
    {
        parent::init();
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return SerializationContext
     */
    public function getSerializationContext()
    {
        return $this->serializationContext;
    }

    /**
     * @param SerializationContext $serializationContext
     */
    public function setSerializationContext($serializationContext)
    {
        $this->serializationContext = $serializationContext;
    }
    
    public function serialize($data)
    {
        return parent::serialize($data);
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function serializeModel($model)
    {
        return $this->serializer->serialize($model, $this->getFormat(), $this->getSerializationContext());
    }
    
    public function serializeModels(array $models)
    {
        return $this->serializer->serialize($models, $this->getFormat(), $this->getSerializationContext());
    }

    public function serializeDataProvider($dataProvider)
    {
        if (null !== $this->collectionEnvelope) {
            throw new \RuntimeException('Collection envelope is not implemented.');
        }
        return parent::serializeDataProvider($dataProvider);
    }
}