<?php

namespace Sebk\SmallOrmForms\Message;

class Message
{
    const FIELD_MANDATORY_ERROR = "FIELD_MANDATORY_ERROR";
    const FIELD_WRONG_FORMAT_ERROR = "FIELD_WRONG_FORMAT_ERROR";
    const BLANK_TEMPLATE = "BLANK_TEMPLATE";

    /**
     * @var string[]
     */
    protected static $templates = [
        self::FIELD_MANDATORY_ERROR => "The field %s is mandatory",
        self::FIELD_WRONG_FORMAT_ERROR => "%s has wrong format",
        self::BLANK_TEMPLATE => "%s",
    ];

    /**
     * @var string
     */
    protected $type;
    /** @var array */
    protected $params;

    /**
     * Message constructor
     * @param string $type
     * @param array $params
     * @throws \Exception
     */
    public function __construct(string $type, array $params = [])
    {
        // Check type
        if (!isset(static::$templates[$type])) {
            throw new \Exception("Message type $type is not managed");
        }

        $this->type = $type;
        $this->params = $params;
    }

    /**
     * Get message as string
     * @return string
     */
    public function get()
    {
        return sprintf(static::$templates[$this->type], ...$this->params);
    }
}
