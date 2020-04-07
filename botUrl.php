<?php

/*
 * Class for processing a single link
 */

class botUrl {

    /*
     * @var string url
     */
    public $url;
    
    /*
     * @var string http_host
     */
    public $host;
    
    /*
     * @var array list info to generation html file
     */
    public $data;
    
    /*
     * @var timestamp start time load parse page
     */
    private $timeStart;
    
    /*
     * @var timestamp end time load parse page
     */
    private $timeEnd;
    
    /*
     * @var count img tags
     */
    public $countImg = 0;
    
    /*
     * @var bool delete obj to class
     */
    public $del = false;

    public function __construct($url) {
        if (self::url_exists($url)) {
            $parse_url = parse_url($url);
            if (!empty($parse_url['host'])) {
                $this->host = $parse_url['host'];
                $this->url = $url;
            } else {
                $this->del = true;
            }
        } else {
            $this->del = true;
        }
    }

    /*
     * start set time
     * 
     * @return void
     */

    private function start() {
        $this->timeStart = time();
    }

    /*
     * end set time
     * 
     * @return void
     */

    private function end() {
        $this->timeEnd = time();
    }

    /*
     * Checking the link itself
     * 
     * @return bool
     */

    public function isSameSite($host) {
        if ($this->host == $host && !$this->del) {
            return true;
        }
        return false;
    }

    /*
     * Check if links are repeated
     * 
     * @return bool
     */

    public function isRepeat($urls) {
        return !empty($urls) ? in_array($this->url, $urls) : false;
    }

    /*
     * Does the page exist
     * 
     * @return bool
     */

    public static function url_exists($url) {
        if (empty($url))
            return false;

        $headers = @get_headers($url);
        if (strpos($headers[0], '200') === false)
            return false;
        else
            return true;
    }

    /*
     * get array src by img
     * 
     * @return array
     */

    public function getScrArray() {
        $contents = file_get_contents($this->url);
        //set matching pattern for img tag source
        $pattern = '/src=[\"\']?([^\"\']?.*(png|jpg|gif))[\"\']?/i';

        //match all img tag source
        preg_match_all($pattern, $contents, $images);

        return !empty($images[1]) ? $images[1] : [];
    }

    /*
     * Returns the count img tags
     * 
     * @return count int
     */

    private function getCountImg() {
        if(self::url_exists($this->url)) {
            $contents = file_get_contents($this->url);
            preg_match_all('#<img [^>]*>#i', $contents, $matches, PREG_SET_ORDER);
            return count($matches);
        } else {
            return null;
        }
    }

    /*
     * Start data collection
     * 
     * @return void
     */

    public function load() {
        $this->start();
        $this->countImg = $this->getCountImg();
        $this->end();
    }

    /*
     * Time spent
     * 
     * @return H:i:s - ms
     */

    public function getTimeLoad() {
        $res = $this->secToArray($this->timeEnd - $this->timeStart);
        return $res['hours'].':'.$res['minutes'].':'.$res['secs'].' - '.($this->timeEnd - $this->timeStart);
    }
    
    
    /*
     * Calculates hours, minutes, and seconds
     * 
     * @return array
     */
    private function secToArray($secs) {
        $res = array();

        $res['days'] = floor($secs / 86400);
        $secs = $secs % 86400;

        $res['hours'] = floor($secs / 3600);
        $secs = $secs % 3600;

        $res['minutes'] = floor($secs / 60);
        $res['secs'] = $secs % 60;

        return $res;
    }

}
