<?php

namespace Sebk\SmallOrmForms\Message;

use Sebk\SmallOrmForms\Utils\AbstractCollection;

class MessageCollection extends AbstractCollection implements \JsonSerializable
{
    /**
     * Check $value is a form message
     * @param bool $value
     * @return bool
     */
    public function checkValue($value)
    {
        return $value instanceof Message;
    }

    /**
     * Merge antoher collection of messages to this collection
     * @param MessageCollection $messages
     * @return $this
     */
    public function merge(MessageCollection $messages)
    {
        foreach ($messages as $message) {
            $this[] = $message;
        }

        return $this;
    }

    /**
     * Convert message collection to json array of strings
     * @return array
     */
    public function jsonSerialize()
    {
        $messagesArray = [];
        /** @var Message $message */
        foreach ($this->array as $message) {
            $messagesArray[] = $message->get();
        }

        return $messagesArray;
    }
}
