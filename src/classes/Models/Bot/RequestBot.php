<?php
namespace App\Models\Bot;

use Http\Request;

class RequestBot extends Request
{
    public $host;
    public $urls = [];

    public function setHost($host){
        $this->host = $host;
    }
    
    public function getHost() {
        return $this->host;
    }
    
    public function addUrl($url) {
        $this->urls[] = $url;
    }
    
    public function getUrls() {
        return $this->urls;
    }
    
    public function getAddress() {
        return $this->address;
    }
    
    
}
