<?php
namespace View\Helper;

use View\Helper\Table\ViewHelperTableHtml;
use InvalidArgumentException;
use View\Helper\ViewHelperTag;

class ViewHelperTable extends ViewHelperTag
{
    public function build_table($array, $href) {
        
        $html = '';
        
        $html .= ViewHelperTableHtml::enableScript();
        $html .= ViewHelperTableHtml::getDonwlodTagA($href);
        
        list($titles, $values) = ViewHelperTableHtml::getArgs($array);
        
        // header row
        $html .= $this->getThead($titles);

        // data rows
        $html .= $this->getTbody($array);

        // finish table and return it
        $html .= $this->getTfoot($titles);
        
        return $this->contentTag('table', $html, [
            'id' => 'parseTable'
        ]);
    }
    
    public function getThead($array) {
        $html = '';
        
        //If title was set then writ
        if (is_array($array)) {
            $html .= "\n  ".$this->tableTag('thead');
            $html .= "\n        ".$this->tableTag('tr');
            foreach ($array[0] as $title) {
                $html .= "\n	" .$this->contentTag('th', $title); 
            }
            $html .= "\n	".$this->tableTag('tr', '/');
            $html .= "\n  ".$this->tableTag('thead', '/');
        }
        return $html;
    }
    
    public function getTfoot($array) {
        $html = '';
        
        //If title was set then writ
        if (is_array($array)) {
            $html .= "\n  ".$this->tableTag('tfoot');
            $html .= "\n        ".$this->tableTag('tr');
            foreach ($array[0] as $title) {
                $html .= "\n	" .$this->contentTag('th', $title); 
            }
            $html .= "\n	".$this->tableTag('tr', '/');
            $html .= "\n  ".$this->tableTag('tfoot', '/');
        }
        return $html;
    }
    
    public function getTbody($array) {
        $html = '';
        
        //If title was set then writ
        if (is_array($array)) {
            $html .= "\n  ".$this->tableTag('tbody');
            foreach ($array as $values) {
                $html .= "\n        ".$this->tableTag('tr');
                    foreach ($values as $info) {
                        $html .= "\n	" .$this->contentTag('td', $info); 
                    }
                $html .= "\n	".$this->tableTag('tr', '/');
            }
            $html .= "\n  ".$this->tableTag('tbody', '/');
            
        }
        return $html;
    }
}
