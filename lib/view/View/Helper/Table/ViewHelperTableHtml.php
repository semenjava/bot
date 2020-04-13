<?php

namespace View\Helper\Table;

/**
 * Table
 *
 * Automatic creation of html tables from the data array
 */
class ViewHelperTableHtml {

    
    public static function enableScript() {
        $html = '<link media="screen" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet">';
        $html .= '<script src="http://code.jquery.com/jquery-1.8.3.js"></script>';
        $html .= '<script type="text/javascript" async="" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>';
        $html .= '<script type="text/javascript" async="" src="/js/table.js"></script>';
        return $html;
    }
    
    public static function getArgs ($param) {
        $titles = $values = [];
        foreach ($param as $key => $value) {
            foreach ($value as $title => $val) {
                $titles[$key][] = $title;
                $values[$key][] = $value;
            }
        }
        return [$titles, $values];
    }
    
    public static function getDonwlodTagA($href) {
        return '<div><a href="'.$href.'">Скачать в csv</a></div>';
    }
}

?>
