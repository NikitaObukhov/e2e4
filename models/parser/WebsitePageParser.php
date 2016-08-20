<?php

namespace app\models\parser;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebsitePageParser implements ParserInterface
{

    /**
     * @var ParserInterface[]
     */
    protected $parsers = array();

    protected $client;

    protected $url;

    public function __construct(Client $client, $url = null, $parsers = [])
    {
        $this->client = $client;
        $this->url = $url;
        foreach($parsers as $parser)
        {
            $this->addParser($parser);
        }
    }

    public function addParser(ParserInterface $parser)
    {
        $this->parsers[] = $parser;
    }

    public function getParser()
    {
        return $this->parsers;
    }

    /**
     * @return null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param null $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function doRequestAndParse()
    {

        $response = $this->doRequest();
        return $this->doParse($response);
    }

    public function doRequest()
    {
        $dom = $this->client->request('GET', $this->getUrl());
        $response = $this->client->getResponse();
        /* @var $response \Symfony\Component\BrowserKit\Response */
        if (200 !== $status = $response->getStatus()) {
            throw new \RuntimeException(sprintf('Request to %s failed: %d (%s)', $this->url, $status, $response->getContent()));
        }
        return $dom;
    }

    public function doParse(Crawler $dom)
    {
        foreach($this->parsers as $parser) {
            foreach($parser->doParse($dom) as $result) {
                yield $result;
            }
        }
    }
}