<?php

namespace app\models\entity;

use app\models\traits\ScalarPrimaryKeyTrait;
use Yii;

/**
 * This is the model class for table "{{%search_request}}".
 *
 * @property integer $search_request_id
 * @property string $created_at
 * @property integer $website_page_id
 * @property string $type
 * @property resource $data
 *
 * @property WebsitePage $websitePage
 * @property SearchResult[] $searchResults
 * @property PageContent[] $pageContents
 */
class SearchRequest extends \yii\db\ActiveRecord
{
    use ScalarPrimaryKeyTrait;

    private $_data;

    public static $typeNamesSingular = [
        'link' => 'Ссылка',
        'text' => 'Текст',
        'image' => 'Картинка'
    ];

    public static $typeNamesPlural = [
        'link' => 'Ссылки',
        'text' => 'Текст',
        'image' => 'Картинки'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%search_request}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'website_page_id', 'type'], 'required'],
            [['created_at'], 'safe'],
            [['website_page_id'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [
                ['website_page_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => WebsitePage::className(),
                'targetAttribute' => ['website_page_id' => 'website_page_id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'search_request_id' => 'Search Request ID',
            'created_at' => 'Created At',
            'website_page_id' => 'Website Page ID',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsitePage()
    {
        return $this->hasOne(WebsitePage::className(), ['website_page_id' => 'website_page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSearchResults()
    {
        return $this->hasMany(SearchResult::className(), ['search_request_id' => 'search_request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageContents()
    {
        return $this->hasMany(PageContent::className(),
            ['page_content_id' => 'page_content_id'])->viaTable('{{%search_result}}',
            ['search_request_id' => 'search_request_id']);
    }

    public function setWebsitePage(WebsitePage $websitePage)
    {
        $this->link('websitePage', $websitePage);
    }

    public function setSearchResults($searchResults)
    {
        foreach ($searchResults as $searchResult) {
            $this->addSearchResult($searchResult);
        }
    }

    public function addSearchResult(SearchResult $searchResult)
    {
        $this->link('searchResult', $searchResult);
    }

    public function setCreatedAt(\DateTime $dateTime)
    {
        $this->created_at = $dateTime->format('Y-m-d H:i:s');
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getSearchResultsCount()
    {
        return $this->getSearchResults()->count();
    }

    public function getData()
    {
        if (null === $this->_data) {
            if (is_resource($this->data)) {
                $this->data = stream_get_contents($this->data);
            }
            if ($this->data) {
                $this->_data = unserialize($this->data);
            }
            else {
                $this->_data = [];
            }
        }
        return $this->_data;
    }

    public function setData($data) {
        $this->data = serialize($data);
    }

    public function getTypeNamesPlural()
    {
        $typeNames = static::$typeNamesPlural;
        $data = $this->getData();
        return array_map(function($type) use($typeNames, $data) {
            if ('text' === $type && isset($data['searchText'])) {
                return sprintf('%s (%s)', $typeNames[$type], $data['searchText']);
            }
            return $typeNames[$type];
        }, explode(',', $this->type));
    }

    public function getTypeNamesSingular()
    {
        $typeNames = static::$typeNamesSingular;
        $data = $this->getData();
        return array_map(function($type) use($typeNames, $data) {
            if ('text' === $type && isset($data['searchText'])) {
                return sprintf('%s (%s)', $typeNames[$type], $data['searchText']);
            }
            return $typeNames[$type];
        }, explode(',', $this->type));
    }

    public function extraFields()
    {
        return ['searchResults', 'websitePage'];
    }
}
