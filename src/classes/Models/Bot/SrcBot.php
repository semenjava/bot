<?php
namespace App\Models\Bot;

use App\Models\Bot\Int\LinkInterface;

class SrcBot implements LinkInterface
{
    private $src;
    
    public function __construct($link) {
        $this->setLink($link);
    }

    public function setLink($link) {
        $this->src = $link;
    }
    
    public function getLink(){
        return $this->src;
    }
}
