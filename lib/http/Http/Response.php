<?php
namespace Http;

class Response {
    
    protected $urlinfo;
    protected $uri;
    protected $url;
    public $content;

    public $header;
    public $latency;
    public $status_code;
    public $errors;

    public function __construct() {
        $url = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://';
        $this->url = $url . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->urlinfo = $this->parceUrl($this->url);
    }
    
    public function parceUrl($url) {
        return parse_url($url);
    }
    
    public function getUri() {
        if(!empty($this->uri)) {
            return $this->uri;
        } else {
            return false;
        }
    }
    
    public function getUrl() {
        if(!empty($this->url)) {
            return $this->url;
        } else {
            return false;
        }
    }
    
    public function getUrlInfo() {
        if(!empty($this->urlinfo)) {
            return $this->urlinfo;
        } else {
            return false;
        }
    }
    
    public function getBasePath() {
        if(!empty($this->urlinfo)) {
            return $this->getUrlInfo()['path'];
        }
    }
    
    public function set($request) {
        $request->execute();
        $this->content = $request->getResponse();
        $this->setHeaders($request->getHeader());
        $this->setLatency($request->getLatency());
        $this->setHttpCode($request->getHttpCode());
        $this->setError($request->getError());
    }
    
    public function setHeaders($headers) {
        $this->headers = explode("\n", $headers);
    }
    
    public function setLatency($latency) {
        $this->latency = $latency;
    }
    
    public function setHttpCode($code) {
        $this->status_code = $code;
    }
    
    public function setError($error) {
        $this->errors[] = $error;
    }
    
    public function redirect($url) {
        header('Location: '.$url);
        exit;
    }
}
