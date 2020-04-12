<?php
namespace App\Models\Bot;

use App\Models\Bot\Int\linkInterface;

class LinkBot 
{
    public $link;
    public $start;
    public $end;

    public function __construct(linkInterface $link) {
        $this->link = $link;
    }
    
    public function startConnect($time) {
        $this->start = $time;
    }
    
    public function endConnect($time) {
        $this->end = $time;
    }
}
