<?php
namespace View;

use View\ViewBase;

/**
 * @package View
 */

/**
 * Concrete class for handling views.
 *
 * @package View
 */
class ViewJson extends ViewBase
{
    /**
     * Processes a template and returns the output.
     *
     * @param string $name  The template to process.
     *
     * @return string  The template output.
     */
    public function render($name = '', $locals = array())
    {
        return json_encode((object)(array)$this);
    }

    /**
     * Satisfy the abstract _run function in View_Base.
     */
    protected function _run()
    {
    }
}
