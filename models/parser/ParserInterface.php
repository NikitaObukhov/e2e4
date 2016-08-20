<?php

namespace app\models\parser;

use Symfony\Component\DomCrawler\Crawler;

interface ParserInterface
{
    public function doParse(Crawler $dom);
}