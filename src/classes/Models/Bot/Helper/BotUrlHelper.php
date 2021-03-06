<?php
namespace App\Models\Bot\Helper;

class BotUrlHelper {
    
    /*
     * Checking the link itself
     * 
     * @return bool
     */

    public static function isSameSite($hostMain, $host) {
        if ($hostMain == $host) {
            return true;
        }
        return false;
    }

    /*
     * Check if links are repeated
     * 
     * @return bool
     */

    public static function isRepeat($url, $urls) {
        return !empty($urls) ? in_array($url, $urls) : false;
    }
    
    /*
     * Does the page exist
     * 
     * @return bool
     */

    public static function url_exists($url) {
        if (empty($url))
            return false;

        $exists = true;
        $file_headers = @get_headers($URL);
        $InvalidHeaders = array('404', '403', '500');
        foreach($InvalidHeaders as $HeaderVal)
        {
                if(strstr($file_headers[0], $HeaderVal))
                {
                        $exists = false;
                        break;
                }
        }
        return $exists;
    }
    
}
