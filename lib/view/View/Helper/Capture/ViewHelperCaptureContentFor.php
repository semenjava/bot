<?php
namespace View\Helper\Capture;

/**
 * @package    View
 * @subpackage Helper
 */

/**
 * An instance of this class is returned by
 * View_Helper_Capture::contentFor().
 *
 * @package    View
 * @subpackage Helper
 */
class ViewHelperCaptureContentFor extends ViewHelperCaptureBase
{
    /**
     * Name that will become "$this->contentForName".
     *
     * @var string
     */
    private $_name;

    /**
     * Starts capturing content that will be stored as $view->contentForName.
     *
     * @param string $name           Name of the content that becomes the
     *                               instance variable name.
     *                               "foo" -> "$this->contentForFoo"
     * @param View_Base $view  A view object.
     */
    public function __construct($name, $view)
    {
        $this->_name = $name;
        $this->_view = $view;
        parent::__construct();
    }

    /**
     * Stops capturing content and stores it in the view.
     */
    public function end()
    {
        $name = 'contentFor' . String::ucfirst($this->_name);
        $this->_view->$name = parent::end();
    }
}
