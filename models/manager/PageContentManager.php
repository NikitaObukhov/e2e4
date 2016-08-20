<?php

namespace app\models\manager;

use app\models\entity\PageContent;

class PageContentManager
{
    /**
     * @param $pageContents PageContent[]
     */
    public function attach($pageContents)
    {
        $hashes = [];
        foreach($pageContents as $pageContent) {
            $hashes[] = $pageContent->createHash();
        }
        var_dump($hashes);
    }
}