<?php

namespace app\models\parser;

use app\models\client\HttpRequestFailedException;
use app\models\entity\WebsitePage;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebsitePageParser implements ParserInterface
{

    /**
     * @var ParserInterface[]
     */
    protected $parsers = array();

    protected $client;

    protected $websitePage;

    public function __construct(Client $client, WebsitePage $websitePage = null, $parsers = [])
    {
        $this->client = $client;
        $this->websitePage = $websitePage;
        foreach($parsers as $parser)
        {
            $this->addParser($parser);
        }
    }

    public function addParser(ParserInterface $parser)
    {
        $this->parsers[] = $parser;
    }

    public function getParsers()
    {
        return $this->parsers;
    }

    /**
     * @return WebsitePage
     */
    public function getWebsitePage()
    {
        return $this->websitePage;
    }

    /**
     * @param WebsitePage $websitePage
     */
    public function setWebsitePage($websitePage)
    {
        $this->websitePage = $websitePage;
    }

    public function doRequestAndParse()
    {

        $response = $this->doRequest();
        return $this->doParse($response);
    }

    public function doRequest($ssl = false)
    {
        $uri = $this->getWebsitePage()->getUri($ssl ? 'https' : 'http');
        $dom = $this->client->request('GET', $uri);
        $response = $this->client->getResponse();
        /* @var $response \Symfony\Component\BrowserKit\Response */
        if (200 !== $status = $response->getStatus()) {
            throw new HttpRequestFailedException($uri, $status);
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