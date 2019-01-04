<?php

/**
 * Entity object
 *
 * @package    RpkUtils
 * @author     Repkit <repkit@gmail.com>
 * @copyright  2018 Repkit
 * @license    MIT <http://opensource.org/licenses/MIT>
 * @since      2018-09-21
 */

namespace RpkUtils\Oop\Abstracts;

abstract class Entity implements \ArrayAccess, \Countable, \JsonSerializable
{

    /**
     * List of properties for the entity class
     * @var array
     */
    protected $_data = array ();

    /**
     * Return json representation
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }

    /**
     * Return the contained properties as an array
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $Offset An offset to check for.
     * @return boolean Returns true on success or false on failure.
     *
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($Offset)
    {
        return array_key_exists($Offset, $this->_data);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $Offset The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($Offset)
    {
        if(array_key_exists($Offset, $this->_data))
                return $this->_data[$Offset];
        return null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $Offset The offset to assign the value to.
     * @param mixed $Value The value to set.
     * @return void
     */
    public function offsetSet($Offset, $Value)
    {
        $this->_data[$Offset] = $Value;
    }

    /**
     * @param $Offset
     * @param $Value
     */
    public function __set($Offset, $Value)
    {
        $this->offsetSet($Offset, $Value);
    }

    /**
     * @param $Offset
     * @return mixed
     */
    public function __get($Offset)
    {
        return $this->offsetGet($Offset);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $Offset The offset to unset.
     * @return void
     */
    public function offsetUnset($Offset)
    {
        unset($this->_data[$Offset]);
    }

    /**
     * Exchange the properties contained in the entity with the ones defined in the $Input array
     * @param array $Input
     * @return \DbManager\Entity
     */
    public function exchangeArray(array $Input)
    {
        if(!  empty($this->_data))
        {
            $this->_data = $this->exchange($this->_data, $Input);
        }
        else
        {
            $this->_data = $Input;
        }
        return $this;
    }

    /**
     * @param array $RowData
     * @return Row
     */
    public function populate(array $RowData)
    {
        $this->exchangeArray($RowData);
        return $this;
    }

    /**
     * @param array/ArrayObject $RowData
     * @return Row
     */
    public function hydrate($Data)
    {
        $this->exchangeArray($this->castToArray($Data));
        return $this;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->_data);
    }

    public function getArrayCopy()
    {
        $this->validate();
        return $this->toArray();
    }

    public function expose()
    {
        //return get_object_vars($this);
        return $this->_data;
    }

    public function serialize()
    {
        return serialize($this->_data);
    }

    public function unSerialize($String)
    {
        return unserialize($String);
    }

    private function exchange(array $DestinationArray, array $SourceArray, $Strict = true)
    {

        if( ! $Strict)
        {
            return array_merge($DestinationArray, $SourceArray);
        }
        foreach ($SourceArray as $key => $value)
        {
            if(array_key_exists($key, $DestinationArray))
            {
                $DestinationArray[$key] = $value;
            }
        }
        unset($SourceArray);
        return $DestinationArray;
    }

    private function castToArray($Object)
    {
        if(is_array($Object)){
            return $Object;
        }

        if(is_object($Object))
        {
            if ($Object instanceof \StdClass)
            {
                return (array)$Object;
            }
            elseif (method_exists($Object, 'toArray'))
            {
                return $Object->toArray();
            }
            elseif (method_exists($Object, 'getArrayCopy'))
            {
                return $Object->getArrayCopy();
            }
            elseif ($Object instanceof \Zend\Db\ResultSet\ResultSetInterface)
            {
                return $Object->toArray();
            }
            elseif ($Object instanceof \Traversable)
            {
                $array = array();
                foreach ($Object as $key => $value)
                {
                    $array[$key] = $value;
                }
                return $this->castToArray($array);
            }
        }
        throw new \Exception("Could not cast to array", 1);
        
    }

    /**
     * Self validation
     * return true on success throw \InvalidArgumentException on failure
     */
    public function validate()
    {
        return true;
    }
    
    /**
     * Get properties
     * @return array
     */
    public function getProperties()
    {
        return array_keys($this->_data);
    }
    
    /**
     * Handle setter and getter type function calls
     * 
     * @param string $Name
     * @param array $Arguments
     */
    public function __call($Name, $Arguments) 
    {
        // setSomething
        if (strpos($Name, 'set') === 0) {
            if (count($Arguments) !== 1 || is_array($Arguments[0]) || is_object($Arguments[0])) {
                // only allow db-compatible values to be passed
                throw new \InvalidArgumentException('Invalid arguments passed');
            }
            $property = substr($Name, 3);
            if (!array_key_exists($property, $this->_data)) {
                throw new \RuntimeException('Invalid property' . $property);
            }
            $this->offsetSet($property, $Arguments[0]);
            
        // getSomething
        } elseif (strpos($Name, 'get') === 0) {
            $property = substr($Name, 3);
            if (!array_key_exists($property, $this->_data)) {
                throw new \RuntimeException('Invalid property: ' . $property);
            }
            return $this->offsetGet($property);
            
        } else {
            throw new \RuntimeException('Invalid method called: ' . $Name);
        }
    }
}