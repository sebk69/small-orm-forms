<?php

namespace Sebk\SmallOrmForms\Type;

class FloatType implements TypeInterface
{
    const TYPE_FLOAT = "float";

    use TypeTrait;

    public function __contruct()
    {
        $this->setType(self::TYPE_FLOAT);
    }

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_FLOAT) && $value != null) {
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
        return (float)$value;
    }
}
