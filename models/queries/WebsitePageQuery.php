<?php

namespace app\models\queries;

use app\models\entity\Website;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[WebsitePage]].
 *
 * @see WebsitePage
 */
class WebsitePageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return WebsitePage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return WebsitePage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byUri($uri)
    {
        $parts = parse_url($uri);
        $host = $parts['host'];
        $path = $parts['path'];
        return $this->joinWith(['website w'])
            ->andWhere(['w.domain' => $host])
            ->andWhere(['path' => $path]);
    }
}
