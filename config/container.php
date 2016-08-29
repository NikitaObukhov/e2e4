<?php


Yii::$container->set('e2e4.browser', 'Goutte\Client');
Yii::$container->set('Symfony\Component\BrowserKit\Client', 'e2e4.browser'); # Default browser implementation
Yii::$container->set('e2e4.parser.link_parser', 'app\models\parser\LinkParser');
Yii::$container->set('e2e4.parser.image_parser', 'app\models\parser\ImageParser');
Yii::$container->set('e2e4.parser.text_parser', 'app\models\parser\TextParser');
Yii::$container->set('e2e4.parser.factory', 'app\models\parser\ParsersFactory');
Yii::$container->set('e2e4.form.search_request', 'app\models\forms\SearchRequestForm');
Yii::$container->set('e2e4.manager.page_content', 'app\models\manager\PageContentManager');
Yii::$container->set('e2e4.serializer.db_to_jms_caster', 'app\models\bridge\DBTypeToJMSCaster');
Yii::$container->set('e2e4.serializer.schema_provider', 'app\models\bridge\ClassSchemaProvider');
Yii::$container->set('e2e4.serializer.metadata_factory', 'app\models\bridge\ActiveRecordClassMetadataFactory');
Yii::$container->set('e2e4.serializer.metadata_driver', 'app\models\bridge\RuntimeDriver');
Yii::$container->set('e2e4.serializer', 'app\models\bridge\JMSSerializer');

