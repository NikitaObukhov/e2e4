<?php

namespace app\models\parser;

use app\models\entity\TextPageContent;
use Symfony\Component\DomCrawler\Crawler;

class TextParser implements ParserInterface
{

    private $searchString;

    public function __construct(string $searchString)
    {
        $this->searchString = $searchString;
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->searchString;
    }

    /**
     * @param string $searchString
     */
    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
    }

    public function doParse(Crawler $dom)
    {
        $xpath = sprintf('//*[contains(text(),\'%s\')]', $this->searchString);
        $nodes = $dom->filterXPath($xpath);
        foreach($nodes as $node) {
            $content = new TextPageContent();
            $content->data = $node->textContent;
            yield $content;
        }
    }
}