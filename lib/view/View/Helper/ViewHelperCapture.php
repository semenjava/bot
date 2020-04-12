<?php
namespace View\Helper;

use View\Helper\Capture\ViewHelperCaptureBase;
use View\Helper\Capture\ViewHelperCaptureContentFor;

/**
 * @package    View
 * @subpackage Helper
 */

/**
 * Capture lets you extract parts of code which can be used in other points of
 * the template or even layout file.
 *
 * @package    View
 * @subpackage Helper
 */
class ViewHelperCapture extends ViewHelperBase
{
    /**
     * Capture allows you to extract a part of the template into an instance
     * variable.
     *
     * You can use this instance variable anywhere in your templates and even
     * in your layout. Example:
     *
     * <code>
     * <?php $capture = $this->capture() ?>
     * Welcome To my shiny new web page!
     * <?php $this->greeting = $capture->end() ?>
     * </code>
     *
     * @return View_Helper_Capture_Base
     */
    public function capture()
    {
        return new ViewHelperCaptureBase();
    }

    /**
     * Calling contentFor() stores the block of markup for later use.
     *
     * Subsequently, you can retrieve it inside an instance variable
     * that will be named "contentForName" in another template
     * or in the layout.  Example:
     *
     * <code>
     * <?php $capture = $this->contentFor("header") ?>
     * <script type="text/javascript">alert('hello world')</script>
     * <?php $capture->end() ?>
     *
     * // Use $this->contentForHeader anywhere in your templates:
     * <?php echo $this->contentForHeader ?>
     * </code>
     *
     * @param string $name  Name of the content that becomes the instance
     *                      variable name. "foo" -> "$this->contentForFoo"
     * @return View_Helper_Capture_ContentFor
     */
    public function contentFor($name)
    {
        return new ViewHelperCaptureContentFor($name, $this->_view);
    }
}
