<?php

namespace Sebk\SmallOrmForms\Type;

class DateType implements TypeInterface
{
    const TYPE_DATE = "date";

    use TypeTrait;
    use FormatTrait;

    public function __construct()
    {
        $this->setType(self::TYPE_DATE);
        $this->setFormat("Y-m-d");
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
