<?php

namespace Sebk\SmallOrmForms\Type;

class Type
{
    public static function get(string $type)
    {
        $class = __NAMESPACE__ . '\\' . ucfirst($type) . 'Type';

        if (!class_exists($class)) {
            throw new TypeNotFoundException("Invalid type ($type)");
        }

        return new $class;
    }
}
