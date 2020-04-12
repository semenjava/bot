<?php
namespace App\Models\Bot;

use App\Models\Bot\Int\DomInterface;
use KubAT\PhpSimple\HtmlDomParser;

class DomHtml implements DomInterface
{
    public $dom;
    private $url;


    public function createDom($url){
        $this->url = $url;
        $this->setDom( HtmlDomParser::file_get_html( $url ) );
    }
    
    public function setDom( $dom ) {
        $this->dom = $dom;
    }
    
    public function getDom() {
        return $this->dom;
    }
    
    /*
     * Returns the count img tags
     * 
     * @return count int
     */

    public function getImgs() {
        $contents = file_get_contents($this->url);
        preg_match_all('#<img [^>]*>#i', $contents, $matches, PREG_SET_ORDER);
        return $matches;
    }
}
