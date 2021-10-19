<?php

namespace Sebk\SmallOrmForms\Type;

class StringType implements TypeInterface
{
    const TYPE_STRING = "string";

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
        return true;
    }

    /**
     * Reformat a value
     * @param $value
     * @return int
     */
    public function reformat($value)
    {
        return (string)$value;
    }

}
