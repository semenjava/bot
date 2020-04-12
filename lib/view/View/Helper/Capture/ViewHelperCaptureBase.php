<?php
namespace View\Helper\Capture;

use View\ViewException;

/**
 * @package    View
 * @subpackage Helper
 */

/**
 * An instance of this class is returned by
 * View_Helper_Capture::capture().
 * 
 * @package    View
 * @subpackage Helper
 */
class ViewHelperCaptureBase
{
    /**
     * Are we currently buffering?
     *
     * @var boolean
     */
    protected $_buffering = true;

    /**
     * Starts capturing.
     */
    public function __construct()
    {
        ob_start();
    }

    /**
     * Stops capturing and returns what was captured.
     *
     * @return string  The captured string.
     * @throws View_Exception
     */
    public function end()
    {
        if ($this->_buffering) {
            $this->_buffering = false;
            $output = ob_get_clean();
            return $output;
        } else {
            throw new ViewException('Capture already ended');
        }
    }
}
