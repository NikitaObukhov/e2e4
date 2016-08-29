<?php

namespace app\models\entity;

use app\models\traits\ScalarPrimaryKeyTrait;
use Yii;

/**
 * This is the model class for table "{{%search_result}}".
 *
 * @property integer $search_request_id
 * @property integer $page_content_id
 *
 * @property PageContent $pageContent
 * @property SearchRequest $searchRequest
 */
class SearchResult extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%search_result}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search_request_id', 'page_content_id'], 'required'],
            [['search_request_id', 'page_content_id'], 'integer'],
            [['page_content_id'], 'exist', 'skipOnError' => true, 'targetClass' => PageContent::className(), 'targetAttribute' => ['page_content_id' => 'page_content_id']],
            [['search_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => SearchRequest::className(), 'targetAttribute' => ['search_request_id' => 'search_request_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'search_request_id' => 'Search Request ID',
            'page_content_id' => 'Page Content ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageContent()
    {
        return $this->hasOne(PageContent::className(), ['page_content_id' => 'page_content_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSearchRequest()
    {
        return $this->hasOne(SearchRequest::className(), ['search_request_id' => 'search_request_id']);
    }

    public function setPageContent(PageContent $pageContent)
    {
        $this->link('pageContent', $pageContent);
    }

    public function setSearchRequest(SearchRequest $searchRequest)
    {
        $this->link('searchRequest', $searchRequest);
    }

    public function extraFields()
    {
        return ['pageContent'];
    }
}
