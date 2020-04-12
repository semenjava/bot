<?php
namespace App\Models\Bot\Int;


interface DomInterface 
{
    public function createDom($url);
    public function setDom($dom);
    public function getDom();
}
