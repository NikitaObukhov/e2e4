<?php

namespace app\models\parser;

use app\models\forms\SearchRequestForm;

class ParserFactory
{

    public function createParserWithParams($type, $params = [])
    {
        return \Yii::$container->get(sprintf('e2e4.parser.%s_parser', $type), $params);
    }

    public function createParserWithFormParams($type, SearchRequestForm $form)
    {
        if ('text' === $type) {
            return $this->createParserWithParams($type, [$form->searchText]);
        }
        return $this->createParserWithParams($type, []);
    }
}