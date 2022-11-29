<?php

namespace Sebk\SmallOrmForms\Form;

use Sebk\SmallOrmCore\Dao\AbstractDao;
use Sebk\SmallOrmCore\Dao\Model;
use Sebk\SmallOrmForms\Message\Message;
use Sebk\SmallOrmForms\Message\MessageCollection;
use Sebk\SmallOrmForms\Type\ArrayType;
use Sebk\SmallOrmForms\Type\BoolType;
use Sebk\SmallOrmForms\Type\DateTimeType;
use Sebk\SmallOrmForms\Type\FloatType;
use Sebk\SmallOrmForms\Type\IntType;
use Sebk\SmallOrmForms\Type\StringType;
use Sebk\SmallOrmForms\Type\Type;
use Sebk\SmallOrmForms\Type\TypeInterface;

abstract class AbstractForm
{
    /** @var Message */
    protected $messageClass = Message::class;

    /** @var Field[] */
    protected $fields;

    /** @var bool */
    protected $strict = false;

    /**
     * Add a new field
     * - default type is string
     * - default mandatory is Field default
     * @param string $key
     * @param string|null $label
     * @param mixed|null $value
     * @param TypeInterface $type
     * @param string|null $mandatory
     * @return $this
     * @throws FieldException
     * @throws \Sebk\SmallOrmForms\Type\TypeNotFoundException
     */
    public function addField(string $key, string $label = null, $value = null, TypeInterface $type = null, string $mandatory = null)
    {
        if (!($type instanceof ArrayType)) {
            // Add field
            if ($mandatory === null) {
                $this->fields[$key] = new Field($type, $label ?? $key, $value);
            } else {
                $this->fields[$key] = new Field($type, $label ?? $key, $value, $mandatory);
            }
        } else {
            if (!is_array($value) && $value !== null) {
                throw new \Exception('Array wrong value. Value must be of type array or null');
            }

            // Add subvalues
            if ($value !== null) {
                foreach ($value as $i => $item) {
                    $fields[] = new Field($type->getSubtype(), $i, $item);
                }

                if ($mandatory === null) {
                    $this->fields[$key] = new Field($type, $label ?? $key, $fields);
                } else {
                    $this->fields[$key] = new Field($type, $label ?? $key, $fields, $mandatory);
                }
            }
        }

        return $this;
    }

    /**
     * Return field value identified by $key
     * @param $key
     * @return mixed
     * @throws FieldNotFoundException
     */
    public function getValue(string $key)
    {
        if (!isset($this->fields[$key])) {
            throw new FieldNotFoundException("Field not found ($key)");
        }

        return $this->fields[$key]->getValue();
    }

    /**
     * Set a field value
     * @param string $key
     * @param $value
     * @return $this
     * @throws FieldException
     * @throws FieldNotFoundException
     */
    public function setValue(string $key, $value)
    {
        if (!isset($this->fields[$key])) {
            throw new FieldNotFoundException("Field not found ($key)");
        }

        $this->fields[$key]->setValue($value);

        return $this;
    }

    /**
     * Return the field orresponding to the key
     * @param $key
     * @return Field
     */
    public function getField($key)
    {
        return $this->fields[$key];
    }

    /**
     * Set form as strict or not
     * @param bool $strict
     * @return $this
     */
    public function setStrict(bool $strict = true)
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * Fill form values from array
     * @param array $array
     * @return $this
     * @throws FieldException
     * @throws FieldNotFoundException
     */
    public function fillFromArray(array $array)
    {
        foreach ($array as $key => $value) {
            if ($this->strict && !isset($this->fields[$key])) {
                throw new \Exception("Field $key does not exists !");
            }

            if (isset($this->fields[$key])) {
                $this->setValue($key, $value);
            }
        }

        return $this;
    }

    /**
     * Fill form values from stdClass
     * @param \stdClass $stdClass
     * @return $this
     * @throws FieldException
     * @throws FieldNotFoundException
     */
    public function fillFromStdClass(\stdClass $stdClass)
    {
        $array = json_decode(json_encode($stdClass), true);

        $this->fillFromArray($array);

        return $this;
    }

    /**
     * Set field if mandatory or not
     * @param string $key
     * @param string $mandatory
     * @return $this
     * @throws \Exception
     */
    public function setFieldMandatory(string $key, string $mandatory = Field::MANDATORY)
    {
        $this->fields[$key]->setMandatory($mandatory);

        return $this;
    }

    /**
     * Set message class
     * @param $messageClass
     * @throws \Exception
     */
    public function setMessageClass($messageClass)
    {
        if (!class_exists($messageClass)) {
            throw new \Exception("Message class $messageClass doesn't exists");
        }
        
        $this->messageClass = $messageClass;
    }

    /**
     * Form validation
     * @return MessageCollection
     */
    public function validate()
    {
        $messages = new MessageCollection;
        $messageClass = $this->messageClass;

        foreach ($this->fields as $key => $field) {
            // Check field is mandatory
            if (!$field->checkMandatory()) {
                $messages[] = new $messageClass(Message::FIELD_MANDATORY_ERROR, [$field->getLabel()]);
                return $messages;
            }

            // check value compliant to field type
            if (!$field->checkFormat()) {
                $messages[] = new $messageClass(Message::FIELD_WRONG_FORMAT_ERROR, [$field->getLabel()]);
            }

            // If field is array, call validate of subforms
            if ($field->getType()->getType() == ArrayType::TYPE_ARRAY) {
                foreach ($field->getValue() as $subfield) {
                    if ($subfield instanceof AbstractForm) {
                        $messages->merge($subfield->validate());
                    } else {
                        // Check field is mandatory
                        if (!$field->checkMandatory()) {
                            $messages[] = new $messageClass(Message::FIELD_MANDATORY_ERROR, [$subfield->getLabel()]);
                        }

                        // check value compliant to field type
                        if (!$field->checkFormat()) {
                            $messages[] = new $messageClass(Message::FIELD_WRONG_FORMAT_ERROR, [$subfield->getLabel()]);
                        }
                    }
                }
            }
        }

        return $messages;
    }

    /**
     * Set the type of a field
     * @param string $key
     * @param string $type
     * @return $this
     * @throws \Sebk\SmallOrmForms\Type\TypeNotFoundException
     */
    public function setFieldType(string $key, string $type)
    {
        $this->fields[$key]->setType(Type::get($type));

        return $this;
    }

}
