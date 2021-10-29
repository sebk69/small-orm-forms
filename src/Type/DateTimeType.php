<?php

namespace Sebk\SmallOrmForms\Type;

class DateTimeType implements TypeInterface
{
    const TYPE_DATE_TIME = "datetime";

    use TypeTrait;

    public function __contruct()
    {
        $this->setType(self::TYPE_DATE_TIME);
    }

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        if (!$value instanceof \DateTime && $value != null) {
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
