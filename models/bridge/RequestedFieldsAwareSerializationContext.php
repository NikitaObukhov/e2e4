<?php

namespace app\models\bridge;

use JMS\Serializer\SerializationContext;

class RequestedFieldsAwareSerializationContext extends SerializationContext
{
    private $requestedFields;

    public function getRequestedFields()
    {
        return $this->requestedFields;
    }

    public function setRequestedFields(array $requestedFields)
    {
        $this->requestedFields = $requestedFields;
    }

}