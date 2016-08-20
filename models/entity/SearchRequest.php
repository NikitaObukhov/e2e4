<?php

namespace app\models\entity;

use Yii;

/**
 * This is the model class for table "{{%search_request}}".
 *
 * @property integer $search_request_id
 * @property string $created_at
 * @property integer $website_page_id
 *
 * @property WebsitePage $websitePage
 */
class SearchRequest extends \yii\db\ActiveRecord
{
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
            [['search_request_id', 'created_at', 'website_page_id'], 'required'],
            [['search_request_id', 'website_page_id'], 'integer'],
            [['created_at'], 'safe'],
            [['website_page_id'], 'exist', 'skipOnError' => true, 'targetClass' => WebsitePage::className(), 'targetAttribute' => ['website_page_id' => 'website_page_id']],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsitePage()
    {
        return $this->hasOne(WebsitePage::className(), ['website_page_id' => 'website_page_id']);
    }
}
