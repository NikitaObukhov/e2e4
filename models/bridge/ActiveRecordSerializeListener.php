<?php

namespace app\models\bridge;

use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\SerializationContext;
use yii\base\Arrayable;

class ActiveRecordSerializeListener
{

    public static function onPostSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();
        $context = $event->getContext();

        return;
        $attributes = $event->getContext()->attributes;
        if ($object instanceof Arrayable && $context instanceof RequestedFieldsAwareSerializationContext) {
            $visitor = $context->getVisitor();
            /* @var $visitor \JMS\Serializer\GenericSerializationVisitor */
            list ($fields, $expand) = $context->getRequestedFields();
            $extra = $object->toArray($fields, $expand, false);
            $factory = \Yii::$container->get('e2e4.serializer.metadata_factory');

            foreach($expand as $fieldToExpand) {
                if (isset($extra[$fieldToExpand])) {
                    $data = $extra[$fieldToExpand];
                 //   $context->pushClassMetadata($factory->getMetadataForClass('app\models\entity\SearchResult'));
                   $visitor->addData($fieldToExpand, $data);


//                    $rs = $visitor->visitArray($data, [], $context);


                }
            }

        }
    }
}