<?php

namespace app\models\entity;

use app\models\parser\ImageParser;
use app\models\queries\PageContentQuery;
use app\models\traits\ScalarPrimaryKeyTrait;
use Yii;

/**
 * This is the model class for table "{{%page_content}}".
 *
 * @property integer $page_content_id
 * @property string $type
 * @property string $hash
 * @property resource $data
 *
 * @property WebsitePageContent[] $websitePageContents
 * @property WebsitePage[] $websitePages
 */
abstract class PageContent extends \yii\db\ActiveRecord
{

    use ScalarPrimaryKeyTrait;

    private $_data;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_content}}';
    }

    public static function myRequiredColumns()
    {
        return ['type', 'hash', 'data'];
    }

    public function init()
    {
        $this->type = static::myType();
        parent::init();
    }

    public function getAttributesWithoutNullPk()
    {
        $attributes = $this->getAttributes();
        $pk = static::primaryKey();
        $pk = reset($pk);
        if (null === $attributes[$pk]) {
            unset($attributes[$pk]);
        }
        return $attributes;
    }

    public static function find()
    {
        if (is_callable([get_called_class(), 'myType'])) { // Called in context of concrete class: filter by it's type
            return new PageContentQuery(get_called_class(), ['type' => static::myType()]);
        }
        return new PageContentQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        $this->type = static::myType();
        $this->hash = $this->createHash();
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'hash', 'data'], 'required'],
            [['type', 'hash', 'data'], 'string'],
            [['hash'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_content_id' => 'Page Content ID',
            'type' => 'Type',
            'hash' => 'Hash',
            'data' => 'Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsitePageContents()
    {
        return $this->hasMany(WebsitePageContent::className(), ['page_content_id' => 'page_content_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsitePages()
    {
        return $this->hasMany(WebsitePage::className(), ['website_page_id' => 'website_page_id'])->viaTable('{{%website_page_content}}', ['page_content_id' => 'page_content_id']);
    }

    abstract public static function myType();

    abstract public function getContent();

    public static function instantiate($row)
    {
        switch ($row['type']) {
            case LinkPageContent::myType():
                return new LinkPageContent();
            case TextPageContent::myType():
                return new TextPageContent();
            case ImagePageContent::myType():
                return new ImagePageContent();
            default:
                throw new \RuntimException('Failed to instantiate PageContent: unknown type %s', $row['type']);
        }
    }

    public function setData($data)
    {
        $this->data = is_scalar($data) ? $data : serialize($data);
        $this->_data = $data;
    }

    public function getData()
    {
        if (null === $this->_data && $this->data) {
            $this->_data = @unserialize($this->data) or $this->data;
        }
        return $this->_data;
    }

    public function createHash()
    {
        return md5($this->data);
    }
}
