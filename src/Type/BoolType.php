<?php

namespace Sebk\SmallOrmForms\Type;

class BoolType implements TypeInterface
{
    const TYPE_BOOL = "bool";

    use TypeTrait;

    public function __contruct()
    {
        $this->setType(self::TYPE_BOOL);
    }

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        if (!is_bool($value)) {
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
        return (bool)$value;
    }
}
