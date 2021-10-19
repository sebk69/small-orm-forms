<?php

namespace Sebk\SmallOrmForms\Form;

use Sebk\SmallOrmForms\Message\Message;
use Sebk\SmallOrmForms\Message\MessageCollection;
use Sebk\SmallOrmForms\Type\Type;
use Sebk\SmallOrmForms\Type\TypeInterface;

abstract class AbstractForm
{
    /** @var Message */
    protected $messageClass = Message::class;

    /** @var Field[] */
    protected $fields;

    /**
     * Add a new field
     * - default type is string
     * - default mandatory is Field default
     * @param mixed $key
     * @param TypeInterface $type
     * @param mixed $value
     * @param string|null $mandatory
     * @return $this
     * @throws FieldException
     */
    public function addField($key, $label = null, $value = null, string $typeString = null, string $mandatory = null)
    {
        // string by default
        $type = Type::get($typeString);

        // Add field
        if ($mandatory === null) {
            $this->fields[$key] = new Field($type, $label ?? $key, $value);
        } else {
            $this->fields[$key] = new Field($type, $label ?? $key, $value, $mandatory);
        }

        return $this;
    }

    /**
     * Return field value identified by $key
     * @param $key
     * @return mixed
     * @throws FieldNotFoundException
     */
    public function getValue($key)
    {
        if (!isset($this->fields[$key])) {
            throw new FieldNotFoundException("Field not found ($key)");
        }

        return $this->fields[$key]->getValue();
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

        if (!($$messageClass::class instanceof Message)) {
            throw new \Exception("Message class $messageClass doesn't implement message");
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
                $fail = true;
            }

            // check value compliant to field type
            if (!$field->checkFormat()) {
                $messages[] = new $messageClass(Message::FIELD_MANDATORY_ERROR, [$field->getLabel()]);
                $fail = true;
            }
        }

        return $messages;
    }
}
