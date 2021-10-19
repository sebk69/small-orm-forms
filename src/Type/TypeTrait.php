<?php

namespace Sebk\SmallOrmForms\Type;

trait TypeTrait
{

    /** @var string */
    protected $type;

    /**
     * Get type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     * @param $type
     * @return $this
     * @throws TypeNotFoundException
     */
    public function setType($type)
    {
        if (!class_exists(__NAMESPACE__ . '\\' . ucfirst($type) . 'Type')) {
            throw new TypeNotFoundException("Invalid type ($type)");
        }

        $this->type = $type;

        return $this;
    }

}
