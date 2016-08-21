<?php

namespace app\models\manager;

use app\models\entity\PageContent;
use app\models\entity\WebsitePage;
use app\models\entity\WebsitePageContent;
use yii\helpers\ArrayHelper;

class PageContentManager
{
    /**
     * Updates content of given page.
     *
     * @param $pageContents PageContent[]
     */
    public function mergeWebsitePageContents($pageContents, WebsitePage $websitePage)
    {
        $hashes = [];
        $map = [];
        foreach($pageContents as $pageContent) {
            $hash = $pageContent->createHash();
            if (false === isset($map[$hash])) {
                $hashes[] = $hash;
                $map[$hash] = $pageContent;
            }
        }
        $existingWebsitePageContents = $websitePage->getPageContents()
            ->indexBy('hash')
            ->all();


        $existing = PageContent::find()->byHashes($hashes)->all();
        foreach($existing as $hash => $existingContent) {
            $map[$hash] = $existingContent;
        }
        $existingHashes = array_keys($existing);

        // Get new page contents
        $new = array_diff($hashes, $existingHashes);

        // Get existing page contents that are not associated with this page yet:
        $newAssociations = array_diff($hashes, array_keys($existingWebsitePageContents));

        // Get page contents that gone from this page:
        $old = array_diff(array_keys($existingWebsitePageContents), $hashes);

        if (count($new) > 0) {
            $newPageContents = array_intersect_key($map, array_flip($new));
            $this->saveNewWebsitePageContents($newPageContents, $websitePage);
            foreach($newPageContents as $hash => $newPageContent) {
                $map[$hash] = $newPageContent;
            }
        }
        if (count($newAssociations) > 0) {
            $newAssociatedPageContents = array_intersect_key($existing, array_flip($newAssociations));
            $this->linkExistingPageContentsToWebsitePage($newAssociatedPageContents, $websitePage);
        }
        if (count($old) > 0) {
            $oldPageContents = array_intersect_key($existingWebsitePageContents, array_flip($old));
            $this->deleteOldWebsitePageContents($oldPageContents, $websitePage);
        }
        return $map;
    }

    /**
     * Create new contents and links them to the given page.
     *
     * @param PageContent[] $newPageContents
     * @param WebsitePage $websitePage
     * @throws \yii\db\Exception
     */
    public function saveNewWebsitePageContents($newPageContents, WebsitePage $websitePage)
    {
        foreach($newPageContents as $newPageContent) {
            $newPageContent->beforeSave(true);
        }
        $rows = ArrayHelper::getColumn($newPageContents, 'attributesWithoutNullPk');

        \Yii::$app->db->createCommand()->batchInsert(PageContent::tableName(), PageContent::myRequiredColumns(), $rows)->execute();
        $newHashes = ArrayHelper::getColumn($newPageContents, 'hash');
        $rows = PageContent::find()
            ->select(PageContent::primaryKey())
            ->addSelect('hash')
            ->where(['hash' => $newHashes])
            ->asArray()
            ->indexBy('hash')
            ->all();
        assert(count($rows) === count($newHashes));

        $pageContentPk = PageContent::scalarPrimaryKey();
        $websitePagePk = WebsitePage::scalarPrimaryKey();
        foreach($rows as $hash => &$row) {
            $newPageContents[$hash]->{$pageContentPk} = $row[$pageContentPk];
            $row[$websitePagePk] = $websitePage->getPrimaryKey();
            unset($row['hash']);
        }
        \Yii::$app->db->createCommand()->batchInsert(WebsitePageContent::tableName(),
            [$pageContentPk, $websitePagePk], $rows)->execute();
    }

    /**
     * Removes given contents from page. If any of contents are not associated with any page anymore, they will
     * be deleted too.
     *
     * @param $oldPageContents PageContent[]
     * @param WebsitePage $websitePage
     * @throws \yii\db\Exception
     */
    public function deleteOldWebsitePageContents($oldPageContents, WebsitePage $websitePage)
    {
        $ids = ArrayHelper::getColumn($oldPageContents, PageContent::scalarPrimaryKey());
        \Yii::$app->db->createCommand()->delete(WebsitePageContent::tableName(), [
            WebsitePage::scalarPrimaryKey() => $websitePage->getPrimaryKey(),
            PageContent::scalarPrimaryKey() => $ids,
        ])->execute();
        // $this->removeOrphanedPageContents($oldPageContents);
    }

    /**
     * Links existing contents to the given page.
     *
     * @param $pageContents PageContent[]
     * @param WebsitePage $websitePage
     * @throws \yii\db\Exception
     */
    public function linkExistingPageContentsToWebsitePage($pageContents, WebsitePage $websitePage)
    {
        $rows = [];
        foreach($pageContents as $pageContent) {
            $rows[] = [
                PageContent::scalarPrimaryKey() => $pageContent->getPrimaryKey(),
                WebsitePage::scalarPrimaryKey() => $websitePage->getPrimaryKey(),
            ];
        }
        \Yii::$app->db->createCommand()->batchInsert(WebsitePageContent::tableName(),
            [PageContent::scalarPrimaryKey(), WebsitePage::scalarPrimaryKey()], $rows)->execute();
    }

    /**
     * Check if any of given contents are not associated with any page anymore and removes them if so.
     *
     * @param $pageContents PageContent[]
     * @throws \yii\db\Exception
     */
    public function removeOrphanedPageContents($pageContents)
    {
        $ids = ArrayHelper::getColumn($pageContents, PageContent::scalarPrimaryKey());
        $idsWithParent = WebsitePageContent::find()
            ->where([PageContent::scalarPrimaryKey() => $ids])
            ->asArray()
            ->indexBy(PageContent::scalarPrimaryKey())
            ->all();
        $orphans = array_diff($ids, array_keys($idsWithParent));
        \Yii::$app->db->createCommand()->delete(PageContent::tableName(), [
            PageContent::scalarPrimaryKey() => $orphans,
        ])->execute();
    }
}