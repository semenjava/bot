<?php
namespace App\Models\Bot;

use App\Models\Bot\Int\DomInterface;

class Dom {
    
    public $dom;

    public function __construct( DomInterface $dom, $url ) {
        $this->dom = $dom;
        $this->dom->createDom( $url );
    }
    
    public function find( $elem ){
        return $this->dom->getDom()->find( $elem );
    }
    
    public function getImgs() {
        return $this->dom->getImgs();
    }
    
}
