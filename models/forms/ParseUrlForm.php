<?php

namespace app\models\forms;

use app\models\entity\PageContent;
use app\models\entity\SearchRequest;
use app\models\entity\SearchResult;
use app\models\entity\Website;
use app\models\entity\WebsitePage;
use app\models\manager\PageContentManager;
use app\models\parser\ParserFactory;
use app\models\parser\WebsitePageParser;
use Yii;
use yii\base\Model;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\validators\UrlValidator;
use yii\validators\Validator;

class ParseUrlForm extends Model
{
    public $url;
    public $parsers = array();
    public $searchText;

    protected $parserFactory;

    protected $pageParser;

    protected $manager;

    public function __construct($config = [], WebsitePageParser $pageParser, ParserFactory $parserFactory,
        PageContentManager $manager)
    {
        parent::__construct($config);
        $this->pageParser = $pageParser;
        $this->parserFactory = $parserFactory;
        $this->manager = $manager;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['url', 'parsers'], 'required'],
            ['searchText', 'required', 'when' => function(ParseUrlForm $form) {
                return false !== array_search('text', $form->parsers);
            }],
            ['searchText', 'validateSearchTextIsEmpty', 'when' => function(ParseUrlForm $form) {
                return false === array_search('text', $form->parsers);
            }]
        ];
    }

    public function validateSearchTextIsEmpty()
    {
        if ('' !== trim($this->searchText)) {
            $this->addError('searchText', 'Unknown argument searchText: it is only in use when "text" parser is
            enabled');
        }
    }

    protected function normalizeUrl()
    {
        $parts = parse_url($this->url);
        if (!isset($parts['scheme'])) {
            $this->url = 'http://'. $this->url;
            return $this->normalizeUrl();
        }
        if (!isset($parts['path'])) {
            $this->url .= '/'; // This is front page.
        }
    }

    public function submit()
    {
        $this->normalizeUrl();
        $parts = parse_url($this->url);
        $websitePage = WebsitePage::find()->byUri($this->url)->one();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$websitePage) {
                $website = Website::findOne([
                    'domain' => $parts['host'],
                ]);
                if (!$website) {
                    $website = new Website();
                    $website->setDomain($parts['host']);
                    $website->save();
                }
                $websitePage = new WebsitePage();
                $websitePage->setPath(trim($parts['path']));
                $websitePage->setWebsite($website);
                $websitePage->save();
            }
            $this->pageParser->setWebsitePage($websitePage);
            foreach ($this->parsers as $parser) {
                $this->pageParser->addParser(
                    $this->parserFactory->createParserWithFormParams($parser, $this)
                );
            }
            $result = $this->pageParser->doRequestAndParse();
            $result = $this->manager->mergeWebsitePageContents($result, $websitePage);
            $searchRequest = new SearchRequest();
            $searchRequest->setType(implode(',', $this->parsers));
            $searchRequest->setCreatedAt(new \DateTime());
            $searchRequest->setWebsitePage($websitePage);
            $searchRequest->save();
            $rows = [];
            foreach($result as $pageContent) {
                $rows[] = [
                    $searchRequest->getPrimaryKey(),
                    $pageContent->getPrimaryKey(),
                ];
            }
            Yii::$app->db->createCommand()->batchInsert(SearchResult::tableName(), [SearchRequest::scalarPrimaryKey()
                , PageContent::scalarPrimaryKey()], $rows)->execute();
            foreach($result as $pageContent) {
                $searchResult = new SearchResult();
             //   $searchResult->setPageContent($pageContent);
              //  $searchRequest->addSearchResult($searchResult);
            }
            $searchRequest->save();
            $transaction->commit();
        }
        catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
