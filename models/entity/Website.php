<?php

namespace app\models\entity;

use Yii;

/**
 * This is the model class for table "{{%website}}".
 *
 * @property integer $website_id
 * @property string $domain
 *
 * @property WebsitePage[] $websitePages
 */
class Website extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%website}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain'], 'required'],
            [['website_id'], 'integer'],
            [['domain'], 'string'],
            [['domain'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'website_id' => 'Website ID',
            'domain' => 'Domain',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsitePages()
    {
        return $this->hasMany(WebsitePage::className(), ['website_id' => 'website_id']);
    }

    public function setDomain(string $domain)
    {
        $this->domain = $domain;
    }

    public function getDomain()
    {
        return $this->domain;
    }
}
