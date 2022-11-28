<?php

namespace Sebk\SmallOrmForms\Form;

use Sebk\SmallOrmForms\Type\ArrayType;
use Sebk\SmallOrmForms\Type\SubFormType;
use Sebk\SmallOrmForms\Type\TypeInterface;

class Field
{
    const MANDATORY = "mandatory";
    const OPTIONAL = "optional";

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
    public function __construct(TypeInterface $type, string $label, $value, string $mandatory = self::OPTIONAL)
    {
        // is mandatory ?
        if (!in_array($mandatory, [self::MANDATORY, self::OPTIONAL])) {
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
     * Force the type of the field
     * @param TypeInterface $type
     */
    public function forceType(TypeInterface $type)
    {
        $this->type = $type;
    }

    /**
     * Set value
     * @param $value
     * @return $this
     * @throws FieldException
     */
    public function setValue($value)
    {
        $this->value = $this->type->reformat($value);

        return $this;
    }

    /**
     * Get value
     * @return mixed
     */
    public function getValue()
    {
        return $this->type->reformat($this->value);
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
     * Set mandatory
     * @param $mandatory
     * @throws \Exception
     */
    public function setMandatory($mandatory = self::MANDATORY)
    {
        if (!in_array($mandatory, [self::MANDATORY, self::OPTIONAL])) {
            throw new \Exception("Mandatory value ($mandatory) is not managed !");
        }

        $this->mandatory = $mandatory;
    }

    /**
     * Check if value is compliant with mandatory constraint
     * @return bool
     */
    public function checkMandatory()
    {
        if ($this->getMandatory() == self::MANDATORY && empty($this->getValue()) && $this->getValue() !== 0) {
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

    /**
     * Get type
     * @return TypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type of field
     * @param TypeInterface $type
     * @return $this
     */
    public function setType(TypeInterface $type)
    {
        $this->type = $type;

        return $this;
    }
}
