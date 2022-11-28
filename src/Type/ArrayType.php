<?php

namespace Sebk\SmallOrmForms\Type;

class ArrayType implements TypeInterface
{
    const TYPE_ARRAY = "array";

    use TypeTrait;

    public function __construct(protected TypeInterface $subtype)
    {
        $this->setType(self::TYPE_ARRAY);
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
            if (!$item->validate()) {
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
        $result = [];
        foreach ($value as $key => $item) {
            $result[$key] = $this->subtype->reformat($item);
        }
        
        return $result;
    }
}