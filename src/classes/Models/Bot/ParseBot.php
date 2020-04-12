<?php
namespace App\Models\Bot;

use App\Models\Bot\Int\ParseInterface;
use App\Models\Bot\RequestBot;
use App\Models\Bot\DomHtml;
use App\Models\Bot\Dom;

class ParseBot implements ParseInterface
{
    public $request;
    public $domHtml;

    public function __construct(RequestBot $request) {
        $this->setRequest($request);
    }

    public function setRequest($request) {
        $this->request = $request;
    }
    
    public function createDom() {
        $this->domHtml = new Dom( new DomHtml(), $this->request->getAddress() );
    } 
     
    public function createDomByLink($link) {
        return new Dom( new DomHtml(), $link );
    }
    
}
