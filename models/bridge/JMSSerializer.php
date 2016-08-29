<?php

namespace app\models\bridge;

use app\models\entity\SearchRequest;
use JMS\Serializer\SerializationContext;
use yii\rest\Serializer;
use \krtv\yii2\serializer\Serializer as KRTVSerializer;

class JMSSerializer extends Serializer
{

    /**
     * @var \krtv\yii2\serializer\Serializer
     */
    private $serializer;

    private $format;

    private $serializationContext;

    public function __construct($config = [], $format = 'json', RequestedFieldsAwareSerializationContext $serializationContext = null)
    {
        parent::__construct($config);
        $this->serializer = \Yii::$app->serializer;
        $this->serializer->getInnerSerializer();
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
     * @return RequestedFieldsAwareSerializationContext
     */
    public function getSerializationContext()
    {
        return $this->serializationContext;
    }

    public function setSerializationContext(RequestedFieldsAwareSerializationContext $serializationContext)
    {
        $this->serializationContext = $serializationContext;
    }
    
    public function serialize($data)
    {
        if (is_string($string = parent::serialize($data))) {
            return $string;
        }
        return $this->serializer->serialize($data, $this->getFormat());
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
        $this->getSerializationContext()->setRequestedFields($this->getRequestedFields());
        return $this->serializer->serialize($model, $this->getFormat(), $this->getSerializationContext());
    }
    
    public function serializeModels(array $models)
    {
        $this->getSerializationContext()->setRequestedFields($this->getRequestedFields());
        return $this->serializer->serialize(array_values($models), $this->getFormat(), $this->getSerializationContext());
    }

    public function serializeDataProvider($dataProvider)
    {
        if (null !== $this->collectionEnvelope) {
            throw new \RuntimeException('Collection envelope is not implemented.');
        }
        return parent::serializeDataProvider($dataProvider);
    }
}