<?php

namespace app\models\entity;

use app\models\queries\WebsitePageQuery;
use app\models\traits\ScalarPrimaryKeyTrait;
use Yii;

/**
 * This is the model class for table "{{%website_page}}".
 *
 * @property integer $website_page_id
 * @property integer $website_id
 * @property string $path
 *
 * @property SearchRequest[] $searchRequests
 * @property Website $website
 * @property WebsitePageContent[] $websitePageContents
 * @property PageContent[] $pageContents
 */
class WebsitePage extends \yii\db\ActiveRecord
{
    use ScalarPrimaryKeyTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%website_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['website_id', 'path'], 'required'],
            [['website_page_id', 'website_id'], 'integer'],
            [['path'], 'string'],
            [['path', 'website_id'], 'unique'],
            [['website_id'], 'exist', 'skipOnError' => true, 'targetClass' => Website::className(), 'targetAttribute' => ['website_id' => 'website_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'website_page_id' => 'Website Page ID',
            'website_id' => 'Website ID',
            'path' => 'Path',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSearchRequests()
    {
        return $this->hasMany(SearchRequest::className(), ['website_page_id' => 'website_page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsite()
    {
        return $this->hasOne(Website::className(), ['website_id' => 'website_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsitePageContents()
    {
        return $this->hasMany(WebsitePageContent::className(), ['website_page_id' => 'website_page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageContents()
    {
        return $this->hasMany(PageContent::className(), ['page_content_id' => 'page_content_id'])->viaTable('{{%website_page_content}}', ['website_page_id' => 'website_page_id']);
    }

    public function getUri($protocol = 'http')
    {
        return sprintf('%s://%s/%s', $protocol, $this->website->domain, $this->path);
    }

    /**
     * @inheritdoc
     * @return PageContentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WebsitePageQuery(get_called_class());
    }

    public function setWebsite(Website $website)
    {
        $this->link('website', $website);
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }
}
