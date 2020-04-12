<?php
namespace View\Helper;

/**
 * @package    View
 * @subpackage Helper
 */

/**
 * @package    View
 * @subpackage Helper
 */
class ViewHelperJavascript extends ViewHelperBase
{
    public function escapeJavascript($javascript)
    {
        return str_replace(array('\\',   "\r\n", "\r",  "\n",  '"',  "'"),
                           array('\0\0', "\\n",  "\\n", "\\n", '\"', "\'"),
                           $javascript);
    }

    public function javascriptTag($content, $htmlOptions = array())
    {
        return $this->contentTag('script',
                                 $this->javascriptCdataSection($content),
                                 array_merge($htmlOptions, array('type' => 'text/javascript')));
    }

    public function javascriptCdataSection($content)
    {
        return "\n//" . $this->cdataSection("\n$content\n//") . "\n";
    }
}
