<?php

namespace Sebk\SmallOrmForms\Type;

class IntType implements TypeInterface
{
    const TYPE_INT = "int";

    use TypeTrait;

    public function __construct()
    {
        $this->setType(self::TYPE_INT);
    }

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        if (!ctype_digit((string)$value) && $value !== null) {
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
