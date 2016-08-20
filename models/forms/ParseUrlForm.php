<?php

namespace app\models\forms;

use app\models\manager\PageContentManager;
use app\models\parser\ParserFactory;
use app\models\parser\WebsitePageParser;
use Yii;
use yii\base\Model;
use app\models\User;
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

    public function submit()
    {
        $this->pageParser->setUrl($this->url);
        foreach($this->parsers as $parser) {
            $this->pageParser->addParser(
                $this->parserFactory->createParserWithFormParams($parser, $this)
            );
        }
        $result = $this->pageParser->doRequestAndParse();
        $this->manager->attach($result);
    }
}
