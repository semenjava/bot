<?php
namespace View\Helper\Text;

use InvalidArgumentException;

/**
 * @package    View
 * @subpackage Helper
 */

/**
 * View helpers for URLs
 *
 * @package    View
 * @subpackage Helper
 */
class ViewHelperTextCycle
{
    /**
     * Array of values to cycle through
     * @var array
     */
    private $_values;

    /**
     * Construct a new cycler
     *
     * @param  array  $values  Values to cycle through
     */
    public function __construct($values)
    {
        if (func_num_args() != 1 || !is_array($values)) {
            throw new InvalidArgumentException();
        }

        if (count($values) < 2) {
            throw new InvalidArgumentException('must have at least two values');
        }

        $this->_values = $values;
        $this->reset();
    }

    /**
     * Returns the current element in the cycle
     * and advance the cycle
     *
     * @return  mixed  Current element
     */
    public function __toString()
    {
        $value = next($this->_values);
        return strval(($value !== false) ? $value : reset($this->_values));
    }

    /**
     * Reset the cycle
     */
    public function reset()
    {
        end($this->_values);
    }

    /**
     * Returns the values of this cycler.
     *
     * @return array
     */
    public function getValues()
    {
        return $this->_values;
    }

}
