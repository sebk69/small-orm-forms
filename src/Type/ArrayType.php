<?php

namespace Sebk\SmallOrmForms\Type;

use Sebk\SmallOrmForms\Form\Field;

class ArrayType implements TypeInterface
{
    const TYPE_ARRAY = "array";

    use TypeTrait;

    public function __construct(protected TypeInterface $subtype)
    {
        $this->setType(self::TYPE_ARRAY);
    }
    
    public function getSubtype(): TypeInterface
    {
        return $this->subtype;
    }

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!$this->subtype->validate($item->getValue())) {
                return false;
            }
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
        if ($value === null) {
            return null;
        }

        /**
         * @var int $key
         * @var Field $item
         */
        foreach ($value as $key => $item) {
            $item->setValue($this->subtype->reformat($item->getValue()));
        }
        
        return $value;
    }
}