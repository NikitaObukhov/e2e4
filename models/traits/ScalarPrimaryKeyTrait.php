<?php

namespace app\models\traits;

trait ScalarPrimaryKeyTrait
{

    public static function scalarPrimaryKey()
    {
        $pk = static::primaryKey();
        return reset($pk);
    }

}