<?php

namespace app\models\bridge;

class ActiveRecordVirtualPropertyMetadata extends ActiveRecordPropertyMetadata
{

    public function getValue($obj)
    {
        return $obj->{$this->name};
    }
}