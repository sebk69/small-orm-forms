<?php

namespace Sebk\SmallOrmForms\Type;

class IntType implements TypeInterface
{
    const TYPE_STRING = "int";

    use TypeTrait;

    public function __contruct()
    {
        $this->setType(self::TYPE_STRING);
    }

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        if ((int)$value != $value) {
            return false;
        }

        return true;
    }

    /**
     * Reformat a value
     * @param $value
     * @return int
     */
    public function reformat($value)
    {
        return (int)$value;
    }
}
