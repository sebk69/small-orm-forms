<?php

namespace Sebk\SmallOrmForms\Type;

abstract class Type
{
    /**
     * Type factory
     * @param string|null $type
     * @return mixed
     * @throws TypeNotFoundException
     */
    public static function get(string $type = null)
    {
        // Fallbask to StringType
        if ($type === null) {
            $type = "string";
        }

        // Get class name
        $class = __NAMESPACE__ . '\\' . ucfirst($type) . 'Type';

        // Error if class not exists
        if (!class_exists($class)) {
            throw new TypeNotFoundException("Invalid type ($type)");
        }

        // Return instance of type
        return new $class;
    }
}
