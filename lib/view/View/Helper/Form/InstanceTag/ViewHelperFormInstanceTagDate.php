<?php
namespace View\Helper\Form\InstanceTag;

/**
 * @package    View
 * @subpackage Helper
 */

/**
 * @package    View
 * @subpackage Helper
 */
class ViewHelperFormInstanceTagDate extends ViewHelperFormInstanceTagBase
{
    public function toLabelTag($text, $options = array())
    {
        return $this->contentTag('label', $text, $options);
    }
    
    public function toDateSelectTag($options) {
        $result = '';
        
        // using the current time for the sake of the example, your timestamp would take the place of this
        $timestamp = time();

        // determine the selected month, day, and year
        $selected_month = date('n', $timestamp);
        $selected_day = date('j', $timestamp);
        $selected_year = date('Y', $timestamp);

        // now, create the drop-down for months
        $month_html = '<select name="date_mo">';
        for ($x = 1; $x < 13; $x++) {
            $month_html .= '<option value='.$x.($selected_month == $x ? ' selected=true' : '' ).'>'.date("F", mktime(0, 0, 0, $x, 1, $selected_year)).'</option>';
        }
        $month_html .= '</select>';
        // output
        $result .= $month_html;


        // create the day drop-down
        $day_html = '<select name="date_day">';
        for ($x = 1; $x < 32; $x++) {
            $day_html .= '<option value='.$x.($selected_day == $x ? ' selected=true' : '' ).'>'.$x.'</option>';
        }   
        $day_html .= '</select>';
        // output
        $result .= $day_html;


        // create the year drop-down
        $year_html = '<select name="date_year">';
        $start_year = date('Y', time());
        $max_year = $start_year + 51;
        for ($x = $start_year; $x < $max_year; $x++) {
            $year_html .= '<option value='.$x.($selected_year == $x ? ' selected=true' : '' ).'>'.$x.'</option>';
        }   
        $year_html .= '</select>';
        // output
        $result .= $year_html;
        
        return $result;
    }
}
