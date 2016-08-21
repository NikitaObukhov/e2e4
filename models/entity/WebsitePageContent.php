<?php

namespace app\models\entity;

use Yii;

/**
 * This is the model class for table "{{%website_page_content}}".
 *
 * @property integer $website_page_id
 * @property integer $page_content_id
 *
 * @property PageContent $pageContent
 * @property WebsitePage $websitePage
 */
class WebsitePageContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%website_page_content}}';
    }

    public static function myColumns()
    {
        return ['website_page_id', 'page_content_id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['website_page_id', 'page_content_id'], 'required'],
            [['website_page_id', 'page_content_id'], 'integer'],
            [['page_content_id'], 'exist', 'skipOnError' => true, 'targetClass' => PageContent::className(), 'targetAttribute' => ['page_content_id' => 'page_content_id']],
            [['website_page_id'], 'exist', 'skipOnError' => true, 'targetClass' => WebsitePage::className(), 'targetAttribute' => ['website_page_id' => 'website_page_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'website_page_id' => 'Website Page ID',
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
    public function getWebsitePage()
    {
        return $this->hasOne(WebsitePage::className(), ['website_page_id' => 'website_page_id']);
    }
}
