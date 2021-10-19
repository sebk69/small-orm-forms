<?php

namespace Sebk\SmallOrmForms\Type;

interface TypeInterface
{

    /**
     * Get type
     * @return mixed
     */
    public function getType();

    /**
     * Get type
     * @param string $type
     * @return $this
     */
    public function setType(string $type);

    /**
     * Validate a value
     * @param $value
     * @return bool
     */
    public function validate($value);

    /**
     * Reformat a value
     * @param $value
     * @return mixed
     */
    public function reformat($value);

}
