<?php

namespace app\models\traits;

trait MagicGettersTrait
{
    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'get') || (0 === strpos($name, 'set') && $isSetter = true)) {
            $property = lcfirst(substr($name, 3));
            $property = $this->camelCaseToLowercase($property);
            $value = $this->__get($property);
            return $value;
        }
    }

    private function camelCaseToLowercase($cameCase) {
        $underscore = preg_replace('/[A-Z]/', '_\\0', $cameCase);
        return strtolower($underscore);
    }
}