<?php

namespace Sebk\SmallOrmForms\Form;

class FormMandatoryFieldException extends FieldValidationException
{
    /** @var string */
    public $fieldKey;

    /**
     * Get field key
     * @return string
     */
    public function getFieldKey()
    {
        return $this->fieldKey;
    }

    /**
     * Set field key
     * @param string $fieldKey
     * @return $this
     */
    public function setFieldKey(string $fieldKey)
    {
        $this->fieldKey = $fieldKey;

        return $this;
    }
}
