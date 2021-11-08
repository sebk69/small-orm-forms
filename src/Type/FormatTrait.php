<?php

namespace Sebk\SmallOrmForms\Type;

trait FormatTrait
{
    /**
     * @var mixed
     */
    protected $format;

    /**
     * Get format
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set format
     * @param mixed $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }
}