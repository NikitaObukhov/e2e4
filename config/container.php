<?php


Yii::$container->set('e2e4.browser', 'Goutte\Client');
Yii::$container->set('Symfony\Component\BrowserKit\Client', 'e2e4.browser'); # Default browser implementation
Yii::$container->set('e2e4.parser.link_parser', 'app\models\parser\LinkParser');
Yii::$container->set('e2e4.parser.image_parser', 'app\models\parser\ImageParser');
Yii::$container->set('e2e4.parser.text_parser', 'app\models\parser\TextParser');
Yii::$container->set('e2e4.parser.factory', 'app\models\parser\ParsersFactory');
Yii::$container->set('e2e4.form.parse_url', 'app\models\forms\ParseUrlForm');
Yii::$container->set('e2e4.manager.page_content', 'app\models\manager\PageContentManager');
