<?php

namespace Sebk\SmallOrmForms\Form;

use Sebk\SmallOrmForms\Type\TypeInterface;

class Field
{
    const MANDATORY = "mandatory";
    const OPTIONNAL = "optionnal";

    /** @var TypeInterface */
    protected $type;
    /** @var mixed */
    protected $value;
    /** @var string */
    protected $mandatory;
    /** @var string */
    protected $label;

    /**
     * Field constructor
     * @param TypeInterface $type
     * @param string $label
     * @param mixed $value
     * @param string $mandatory
     * @throws FieldException
     */
    public function __construct(TypeInterface $type, string $label, $value, string $mandatory = self::OPTIONNAL)
    {
        // is mandatory ?
        if (!in_array($mandatory, [self::MANDATORY, self::OPTIONNAL])) {
            throw new FieldException("Wrong mandatory value");
        }
        $this->mandatory = $mandatory;

        // Set type
        $this->type = $type;

        // Set label
        $this->label = $label;

        // Set value
        $this->setValue($value);
    }

    /**
     * Set value
     * @param $value
     * @return $this
     * @throws FieldException
     */
    public function setValue($value)
    {
        if (!$this->type->validate($value)) {
            throw new FieldException("Field value must be of type " . $this->type->getType());
        }

        $this->value = $this->type->reformat($value);

        return $this;
    }

    /**
     * Get value
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get mandatory
     * @return string
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Check if value is compliant with mandatory constraint
     * @return bool
     */
    public function checkMandatory()
    {
        if ($this->getMandatory() == self::MANDATORY && empty($this->getValue())) {
            return false;
        }

        return true;
    }

    /**
     * Check if value is compliant with type
     * @return bool
     */
    public function checkFormat()
    {
        return $this->type->validate($this->value);
    }

    /**
     * Get field label
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
