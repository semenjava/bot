<?php
namespace App;

use View\ViewBase;

/**
 * @package View
 */

/**
 * Concrete class for handling views.
 *
 * @package View
 */
class View extends ViewBase
{
    public $header = '';
    public $footer = '';
    public $form;


    /**
     * Includes the template in a scope with only public variables.
     *
     * @param string  The template to execute. Not declared in the function
     *                signature so it stays out of the view's public scope.
     * @param array   Any local variables to declare.
     */
    protected function _run()
    {
        // Set local variables.
        if (is_array(func_get_arg(1))) {
            foreach (func_get_arg(1) as $key => $value) {
                ${$key} = $value;
            }
        }

        include func_get_arg(0);
    }
    
    public function extends($name) {
        // Find the template file name.
        $this->_file = $this->_template($name);

        // Remove $name from local scope.
        unset($name);
        
        ob_start();
        $this->_run($this->_file, []);
        $content = ob_get_clean();
        
        $content = explode('{%block_content%}', $content);
        
        $this->header = $content[0];
        $this->footer = $content[1];
    }
}
