<?php
namespace App\Models\Bot\Int;


interface ParseInterface
{
    public function setRequest($request);
    public function createDom();
    
}
