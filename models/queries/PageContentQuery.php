<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[PageContent]].
 *
 * @see PageContent
 */
class PageContentQuery extends \yii\db\ActiveQuery
{

    public $type;

    public function prepare($builder)
    {
        if (null !== $this->type) {
            $this->andWhere(['type' => $this->type]);
        }
        return parent::prepare($builder);
    }

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

    public function byHashes(array $hashes)
    {
        return $this->andWhere(['hash' => $hashes])->indexBy('hash');
    }
}
