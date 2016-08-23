<?php

namespace app\models\traits;

trait MagicGettersTrait
{
    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'get')) {
            $property = lcfirst(substr($name, 3));

 /*           $property = $this->camelCaseToUnderscore($property);*/
            $value = $this->__get($property);
            return $value;
        }
    }

    private function camelCaseToUnderscore($cameCase) {
        $underscore = preg_replace('/[A-Z]/', '_\\0', $cameCase);
        return strtolower($underscore);
    }
}