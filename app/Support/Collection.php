<?php

namespace App\Support;

use Traversable;

/**
 * Class Collection
 *
 * @package    App
 * @subpackage App\Collection
 */
class Collection implements \IteratorAggregate, \ArrayAccess, \Countable
{

    /**
     * @var array
     */
    protected $collection;

    /**
     * Constructor.
     *
     * @param array $collection
     */
    public function __construct(array $collection = [])
    {
        $this->collection = $collection;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->collection;
    }

    /**
     * Count elements of an object
     *
     * @return int
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Retrieve an external iterator
     *
     * @return Traversable
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     *
     * @return boolean
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->collection);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->collection[$offset];
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->collection[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }

    /**
     * @param string $name
     * @param null   $default
     *
     * @return mixed|null
     */
    public function get($name, $default = null)
    {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        }

        return $default;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($name, $value)
    {
        $this->offsetSet($name, $value);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function remove($name)
    {
        if ($this->offsetExists($name)) {
            $this->offsetUnset($name);
        }

        return $this;
    }

    /**
     * Returns the first element
     *
     * @return mixed|null
     */
    public function first()
    {
        if (false !== $result = reset($this->collection)) {
            return $result;
        }

        return null;
    }

    /**
     * Returns the last element
     *
     * @return mixed
     */
    public function last()
    {
        if (false !== $result = end($this->collection)) {
            return $result;
        }

        return null;
    }

    /**
     * @param \Closure $callback
     *
     * @return static
     */
    public function filter(\Closure $callback)
    {
        $results = array_filter($this->collection, $callback);

        return new static($results);
    }

    /**
     * @param \Closure $callback
     *
     * @return static
     */
    public function map(\Closure $callback)
    {
        $results = array_map($callback, $this->collection);

        return new static($results);
    }

    /**
     * @param mixed $collection
     *
     * @return $this
     */
    public function merge($collection)
    {
        if ($collection instanceof Collection) {
            $collection = $collection->toArray();
        }

        $this->collection = array_merge($this->collection, $collection);

        return $this;
    }

    /**
     * @param \Closure $callback
     * @param mixed    $initial
     *
     * @return static
     */
    public function reduce(\Closure $callback, $initial = null)
    {
        $results = array_reduce($this->collection, $callback, $initial);

        return new static($results);
    }

    /**
     * @param bool $preserveKeys
     *
     * @return static
     */
    public function reverse($preserveKeys = null)
    {
        return new static(array_reverse($this->collection, $preserveKeys));
    }

    /**
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->collection));
    }

    /**
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->collection));
    }
}
