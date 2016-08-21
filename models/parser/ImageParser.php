<?php

namespace app\models\parser;

use app\models\entity\ImagePageContent;
use Symfony\Component\DomCrawler\Crawler;

class ImageParser implements ParserInterface
{

    public function doParse(Crawler $dom)
    {
        $images = $dom->filterXpath('//img')->images();
        foreach($images as $image) {
            $content = new ImagePageContent();
            $content->setData($image->getUri());
            yield $content;
        }
    }
}