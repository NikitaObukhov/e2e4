<?php

namespace app\models\traits;

trait WhoAmITrait
{
    public static function getClass()
    {
        return get_called_class();
    }
}