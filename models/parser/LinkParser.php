<?php

namespace app\models\parser;

use app\models\entity\LinkPageContent;
use Symfony\Component\DomCrawler\Crawler;

class LinkParser implements ParserInterface
{

    public function doParse(Crawler $dom)
    {
        $links = $dom->filterXpath('//a')->links();
        foreach($links as $link) {
            $content = new LinkPageContent();
            $content->data = array(
                'text' => $link->getNode()->textContent,
                'uri' => $link->getUri(),
            );
            yield $content;
        }
    }
}