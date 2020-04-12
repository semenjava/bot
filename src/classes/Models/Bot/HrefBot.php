<?php
namespace App\Models\Bot;

use App\Models\Bot\Int\LinkInterface;

class HrefBot implements LinkInterface
{
    private $href;
    
    public function __construct($link) {
        $this->setLink($link);
    }

    public function setLink($link) {
        $this->href = $link;
    }
    
    public function getLink(){
        return $this->href;
    }
    
    public function getParseUrl() {
        return parse_url($this->getLink());
    }
}
