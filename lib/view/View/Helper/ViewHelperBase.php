<?php
namespace View\Helper;

/**
 * @package    View
 * @subpackage Helper
 */

/**
 * Abstract class for View_Helper objects.
 *
 * All helpers hold a link back to the instance of the view.  The undefined
 * property handlers (__get()/__call() methods) are used to mix helpers
 * together, effectively placing them on the same pane of glass (the view) and
 * allowing any helper to call any other.
 *
 * @package    View
 * @subpackage Helper
 */
abstract class ViewHelperBase
{
    /**
     * The parent view invoking the helper.
     *
     * @var View
     */
    protected $_view;

    /**
     * Creates a helper for $view.
     *
     * @param View $view The view to help.
     */
    public function __construct($view)
    {
        $this->_view = $view;
        $view->addHelper($this);
    }

    /**
     * Proxy on undefined property access (get).
     */
    public function __get($name)
    {
        return $this->_view->$name;
    }

    /**
     * Proxy on undefined property access (set).
     */
    public function __set($name, $value)
    {
        return $this->_view->$name = $value;
    }

    /**
     * Call chaining so members of the view can be called (including other
     * helpers).
     *
     * @param string $method  The method.
     * @param array $args     The parameters for the method.
     *
     * @return mixed  The result of the method.
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->_view, $method), $args);
    }
}
