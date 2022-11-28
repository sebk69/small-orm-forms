<?php

namespace Sebk\SmallOrmForms\Type;

use Sebk\SmallOrmForms\Form\AbstractForm;
use Sebk\SmallOrmForms\Form\FieldException;

class SubFormType implements TypeInterface
{
    const TYPE_SUBFORM = "subform";

    use TypeTrait;

    public function __construct(protected string $formClass)
    {
        $this->setType(self::TYPE_SUBFORM);
    }

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        if (!$value instanceof $this->formClass) {
            return false;
        }
        
        return count($value->validate()) == 0;
    }

    /**
     * Reformat a value
     * @param $value
     * @return AbstractForm
     */
    public function reformat($value)
    {
        if ($value instanceof $this->formClass) {
            return $value;
        }
        
        if (is_array($value)) {
            return new ($this->formClass)($value);
        }
        
        throw new FieldException('Value is not compatible whith ' . $this->formClass . ' subform');
    }

}
