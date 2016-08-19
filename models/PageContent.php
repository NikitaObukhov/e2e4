<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%page_content}}".
 *
 * @property integer $page_content_id
 * @property integer $page_id
 * @property string $type
 * @property resource $data
 */
class PageContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_content_id', 'page_id', 'type', 'data'], 'required'],
            [['page_content_id', 'page_id'], 'integer'],
            [['type', 'data'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_content_id' => 'Page Content ID',
            'page_id' => 'Page ID',
            'type' => 'Type',
            'data' => 'Data',
        ];
    }

    /**
     * @inheritdoc
     * @return PageContentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageContentQuery(get_called_class());
    }
}
