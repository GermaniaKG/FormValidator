<?php
namespace Germania\FormValidator;

use Psr\Container\ContainerInterface;

class InputContainer implements ContainerInterface, \ArrayAccess
{

    /**
     * @var array
     */
    public $data;

    /**
     * @param array $data     Input values (optional)
     * @param array $defaults Default values (optional)
     */
    public function __construct( array $data = array(), array $defaults = array() )
    {
        $this->data = array_merge($defaults, $data);
    }


    /**
     * @implements ContainerInterface
     */
    public function get( $offset )
    {
        if ($this->offsetExists( $offset )) {
            return $this->data[ $offset ];
        }
        throw new NotFoundException;
    }

    /**
     * @implements ContainerInterface
     */
    public function has( $offset )
    {
        return $this->offsetExists( $offset );
    }


    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->data;        
    }


    /**
     * @implements ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->data[ $offset ]);
    }

    /**
     * @implements ArrayAccess
     */
    public function offsetSet($offset, $value)
    {
        $this->data[ $offset ] = $value;
    }

    /**
     * @implements ArrayAccess
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists( $offset ) ? $this->data[ $offset ] : null;
    }

    /**
     * @implements ArrayAccess
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[ $offset ]);
        }
    }
}
