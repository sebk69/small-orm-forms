<?php

namespace Sebk\SmallOrmForms\Type;

class PhpFilterType implements TypeInterface
{
    const TYPE_PHP_FILTER = "phpFilter";

    use TypeTrait;
    use FormatTrait;

    public function __construct()
    {
        $this->setType(self::TYPE_PHP_FILTER);
    }

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        return filter_var($value, $this->getFormat());
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
