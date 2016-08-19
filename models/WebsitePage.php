<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%website_page}}".
 *
 * @property integer $website_page_id
 * @property integer $website_id
 * @property string $path
 *
 * @property Website $website
 */
class WebsitePage extends \yii\db\ActiveRecord
{
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
            [['website_page_id', 'website_id', 'path'], 'required'],
            [['website_page_id', 'website_id'], 'integer'],
            [['path'], 'string'],
            [['path'], 'unique'],
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
    public function getWebsite()
    {
        return $this->hasOne(Website::className(), ['website_id' => 'website_id']);
    }
}
