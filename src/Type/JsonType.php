<?php

namespace Sebk\SmallOrmForms\Type;

class JsonType implements TypeInterface
{
    const TYPE_JSON = "json";

    use TypeTrait;

    public function __contruct()
    {
        $this->setType(self::TYPE_JSON);
    }

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        if (json_encode($value) === false) {
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
        return $value;
    }
}
