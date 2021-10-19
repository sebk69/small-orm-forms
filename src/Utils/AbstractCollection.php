<?php

namespace Sebk\SmallOrmForms\Utils;

abstract class AbstractCollection implements \ArrayAccess, \Countable, \Iterator
{
    // Position for iterator
    protected $position = 0;

    // Array
    protected $array = [];

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws WrongProcessingType
     */
    public function offsetSet($offset, $value)
    {
        if (!$this->checkValue($value)) {
            throw new \Exception("Invalid value for collection of type " . get_class($this));
        }

        if ($offset === null) {
            $offset = count($this->array);
            while (isset($this->array[$offset])) {
                $offset++;
            }
        }

        $this->array[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->array[$offset]);
        }
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->array[$offset];
    }

    /**
     * Set iterator to first task
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->array[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    /**
     * @return bool|float|int|string|null
     */
    public function key() {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->array[$this->position]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->array);
    }

    /**
     * Clear collection
     */
    public function clear()
    {
        $this->position = 0;
        $this->array = [];
    }

    /**
     * @param bool $value
     * @return bool
     */
    abstract public function checkValue($value);

}
