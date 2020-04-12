<?php
namespace View\Helper;

use View\ViewBase;

use View\Helper\Form\InstanceTag\ViewHelperFormInstanceTagForm;
use View\Helper\ViewHelperFormTag;
use View\Helper\Form\ViewHelperFormBuilder;

/**
 * @package    View
 * @subpackage Helper
 */

/**
 * @package    View
 * @subpackage Helper
 */
class ViewHelperForm extends ViewHelperBase
{
    public $_instanceTag = 'ViewHelperFormInstanceTagForm';

    public function formFor($objectName)
    {
        $args = func_get_args();
        $options = is_array(end($args)) ? array_pop($args) : array();

        if (isset($options['url'])) {
            $urlOptions = $options['url'];
            unset($options['url']);
        } else {
            $urlOptions = array();
        }

        if (isset($options['html'])) {
            $htmlOptions = $options['html'];
            unset($options['url']);
        } else {
            $htmlOptions = array();
        }
        
            echo $this->formTag($urlOptions, $htmlOptions);
        

        $options['end'] = '</form>';

        $args[] = $options;
        return call_user_func_array(array($this, 'fieldsFor'), $args);
    }

    public function fieldsFor($objectName)
    {
        $args = func_get_args();
        $options = is_array(end($args)) ? array_pop($args) : array();
        $object  = isset($args[1]) ? $args[1] : null;

        $builder = isset($options['builder'])
            ? $options['builder']
            : ViewBase::$defaultFormBuilder;

        if($builder == 'ViewHelperFormBuilder') {
            return new ViewHelperFormBuilder($objectName, $object, $this->_view, $options);
        } else {        
            return new $builder($objectName, $object, $this->_view, $options);
        }
    }
    

    /**
     * Returns a label tag tailored for labelling an input field for a
     * specified attribute (identified by $method) on an object assigned to the
     * template (identified by $objectName).
     *
     * The text of label will default to the attribute name unless you specify
     * it explicitly. Additional options on the label tag can be passed as a
     * hash with $options. These options will be tagged onto the HTML as an
     * HTML element attribute as in the example shown.
     *
     * Examples:
     *
     * <code>
     * $this->label('post', 'title');
     * // => <label for="post_title">Title</label>
     *
     * $this->label('post', 'title', 'A short title')
     * // => <label for="post_title">A short title</label>
     *
     * $this->label('post', 'title', 'A short title',
     *              array('class' => 'title_label'));
     * // => <label for="post_title" class="title_label">A short title</label>
     * </code>
     */
    public function label($objectName, $method, $text, $options = array())
    {
        $object = isset($options['object']) ? $options['object'] : null;
        unset($options['object']);
        $tag = new ViewHelperFormInstanceTagForm($objectName, $method, $this->_view, $object);
        return $tag->toLabelTag($text, $options);
    }

    public function textField($objectName, $method, $options = array())
    {
        $object = isset($options['object']) ? $options['object'] : null;
        unset($options['object']);
        $tag = new ViewHelperFormInstanceTagForm($objectName, $method, $this->_view, $object);
        return $tag->toInputFieldTag('text', $options);
    }

    public function passwordField($objectName, $method, $options = array())
    {
        $object = isset($options['object']) ? $options['object'] : null;
        unset($options['object']);
        $tag = new ViewHelperFormInstanceTagForm($objectName, $method, $this->_view, $object);
        return $tag->toInputFieldTag('password', $options);
    }

    public function hiddenField($objectName, $method, $options = array())
    {
        $object = isset($options['object']) ? $options['object'] : null;
        unset($options['object']);
        $tag = new ViewHelperFormInstanceTagForm($objectName, $method, $this->_view, $object);
        return $tag->toInputFieldTag('hidden', $options);
    }

    public function fileField($objectName, $method, $options = array())
    {
        $object = isset($options['object']) ? $options['object'] : null;
        unset($options['object']);
        $tag = new ViewHelperFormInstanceTagForm($objectName, $method, $this->_view, $object);
        return $tag->toInputFieldTag('file', $options);
    }

    public function checkBox($objectName, $method, $options = array(),
                             $checkedValue = '1', $uncheckedValue = '0')
    {
        $object = isset($options['object']) ? $options['object'] : null;
        unset($options['object']);
        $tag = new ViewHelperFormInstanceTagForm($objectName, $method, $this->_view, $object);
        return $tag->toCheckBoxTag($options, $checkedValue, $uncheckedValue);
    }

    public function radioButton($objectName, $method, $tagValue, $options = array())
    {
        $object = isset($options['object']) ? $options['object'] : null;
        unset($options['object']);
        $tag = new ViewHelperFormInstanceTagForm($objectName, $method, $this->_view, $object);
        return $tag->toRadioButtonTag($tagValue, $options);
    }

    public function textArea($objectName, $method, $options = array())
    {
        $object = isset($options['object']) ? $options['object'] : null;
        unset($options['object']);
        $tag = new ViewHelperFormInstanceTagForm($objectName, $method, $this->_view, $object);
        return $tag->toTextAreaTag($options);
    }
    
    /*
     * use formTag
     */
//    public function formTag($urlForOptions = array(), $options = array()) // , *parameters_for_url
//    {
//        $htmlOptions = $this->htmlOptionsForForm($urlForOptions, $options );  // , *parameters_for_url
//        return $this->formTagHtml($htmlOptions);
//    }
//
//    public function endFormTag()
//    {
//        return '</form>';
//    }
//
//    public function selectTag($name, $optionTags = null, $options = array())
//    {
//        return $this->contentTag('select', $optionTags,
//                                 array_merge(array('name' => $name, 'id' => $name), $options));
//    }
//
//    public function optionTag($value = null, $label = null, $selected = false, $options = array())
//    {
//        return $this->contentTag('option', $label, array_merge(array(
//            'selected' => $selected,
//            'value' => $value
//        ), $options));
//    }
//
//    public function textFieldTag($name, $value = null, $options = array())
//    {
//        return $this->tag('input', array_merge(array('type'  => 'text',
//                                                     'name'  => $name,
//                                                     'id'    => $name,
//                                                     'value' => $value),
//                                               $options));
//    }
//
//    public function hiddenFieldTag($name, $value = null, $options = array())
//    {
//        return $this->textFieldTag($name, $value, array_merge($options, array('type' => 'hidden')));
//    }
//
//    public function fileFieldTag($name, $options = array())
//    {
//        return $this->textFieldTag($name, null, array_merge($options, array('type' => 'file')));
//    }
//
//    public function passwordFieldTag($name = 'password', $value = null, $options = array())
//    {
//        return $this->textFieldTag($name, $value, array_merge($options, array('type' => 'password')));
//    }
//
//    public function textAreaTag($name, $content = null, $options = array())
//    {
//        if (isset($options['size'])) {
//            $size = $options['size'];
//            unset($options['size']);
//            if (strpos($size, 'x') !== false) {
//                list($options['cols'], $options['rows']) = explode('x', $size);
//            }
//        }
//
//        return $this->contentTag('textarea', $content,
//                                 array_merge(array('name' => $name, 'id' => $name), $options));
//    }
//
//    public function checkBoxTag($name, $value = '1', $checked = false, $options = array())
//    {
//        $htmlOptions = array_merge(array('type'  => 'checkbox',
//                                         'name'  => $name,
//                                         'id'    => $name,
//                                         'value' => $value,
//                                         'checked' => $checked), $options);
//
//        return $this->tag('input', $htmlOptions);
//    }
//
//    public function radioButtonTag($name, $value, $checked = false, $options = array())
//    {
//        $prettyTagValue = preg_replace('/\s/', '_', $value);
//        $prettyTagValue = String::lower(preg_replace('/(?!-)\W/', '', $prettyTagValue));
//
//        $htmlOptions = array_merge(array('type'  => 'radio',
//                                         'name'  => $name,
//                                         'id'    => "{$name}_{$prettyTagValue}",
//                                         'value' => $value,
//                                         'checked' => $checked), $options);
//
//        return $this->tag('input', $htmlOptions);
//    }
//
//    public function submitTag($value = 'Save changes', $options = array())
//    {
//        if (isset($options['disableWith'])) {
//            $disableWith = $options['disableWith'];
//            unset($options['disableWith']);
//
//            $options['onclick'] = implode(';', array(
//                "this.setAttribute('originalValue', this.value)",
//                "this.disabled=true",
//                "this.value='$disableWith'",
//                "{$options['onclick']}",
//                "result = (this.form.onsubmit ? (this.form.onsubmit() ? this.form.submit() : false) : this.form.submit())",
//                "if (result == false) { this.value = this.getAttribute('originalValue'); this.disabled = false }",
//                "return result"
//            ));
//        }
//
//        return $this->tag('input', array_merge(array('type' => 'submit', 'name' => 'commit', 'value' => $value),
//                                               $options));
//    }
//
//    public function imageSubmitTag($source, $options = array())
//    {
//        // source is passed to View_Helper_Asset->imagePath
//        return $this->tag('input', array_merge(array('type' => 'image',
//                                                     'src'  => $this->imagePath($source)),
//                                               $options));
//    }
//
//    private function extraTagsForForm($htmlOptions)
//    {
//        $method = isset($htmlOptions['method']) ? String::lower($htmlOptions['method']) : '';
//        if ($method == 'get') {
//            $htmlOptions['method'] = 'get';
//            return array('', $htmlOptions);
//        } else if ($method == 'post' || $method == '') {
//            $htmlOptions['method'] = 'post';
//            return array('', $htmlOptions);
//        } else {
//            $htmlOptions['method'] = 'post';
//            $extraTags = $this->contentTag('div',
//                             $this->tag('input', array('type'  => 'hidden', 'name'  => '_method',
//                                                       'value' => $method)), array('style' => 'margin:0;padding:0'));
//            return array($extraTags, $htmlOptions);
//        }
//
//    }
//
//    private function formTagHtml($htmlOptions)
//    {
//        list($extraTags, $htmlOptions) = $this->extraTagsForForm($htmlOptions);
//        return substr($this->contentTag('form', '', $htmlOptions), 0, -7)
//            . $extraTags;
//    }
//
//    /** @todo url_for */
//    private function htmlOptionsForForm($urlForOptions, $options)
//    {
//        if (isset($options['multipart'])) {
//            unset($options['multipart']);
//            $options['enctype'] = 'multipart/form-data';
//        }
//
//        $options['action'] = $this->urlFor($urlForOptions); // , *parameters_for_url
//        // @todo :
////         html_options["action"]  = url_for(url_for_options, *parameters_for_url);
//
//        return $options;
//    }
//    
//    /*
//     * ViewHelperUrl
//     */
//    public function urlFor($first = array(), $second = array())
//    {
//        return is_string($first) ? $first : $this->controller->getUrlWriter()->urlFor($first, $second);
//    }
}
