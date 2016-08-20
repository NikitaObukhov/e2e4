<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[PageContent]].
 *
 * @see PageContent
 */
class PageContentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PageContent[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PageContent|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
