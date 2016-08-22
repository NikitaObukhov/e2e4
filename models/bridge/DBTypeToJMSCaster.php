<?php

namespace app\models\bridge;

class DBTypeToJMSCaster
{
    public function cast($type)
    {
        switch($type) {
            case 'timestamp':
                return 'string';
            case 'binary':
                return null;
            default:
                return $type;
        }
    }
}