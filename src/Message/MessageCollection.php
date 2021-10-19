<?php

namespace Sebk\SmallOrmForms\Message;

use Sebk\SmallOrmForms\Utils\AbstractCollection;

class MessageCollection extends AbstractCollection
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
}
