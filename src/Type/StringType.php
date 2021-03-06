<?php

namespace Sebk\SmallOrmForms\Type;

class StringType implements TypeInterface
{
    const TYPE_STRING = "string";

    use TypeTrait;

    public function __construct()
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
     * @return string
     */
    public function reformat($value)
    {
        return $value;
    }

}
