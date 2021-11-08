<?php

namespace Sebk\SmallOrmForms\Type;

class DateTimeType implements TypeInterface
{
    const TYPE_DATE_TIME = "datetime";

    use TypeTrait;
    use FormatTrait;

    public function __construct()
    {
        $this->setType(self::TYPE_DATE_TIME);
        $this->setFormat("Y-m-d H:i:s");
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
     * @return \DateTime|null
     */
    public function reformat($value)
    {
        if (!$value instanceof \DateTime && !empty($value)) {
            $value = \DateTime::createFromFormat($this->getFormat(), $value);
        } elseif (empty($value)) {
            return null;
        }

        return $value;
    }
}
