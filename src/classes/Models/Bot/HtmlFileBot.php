<?php
namespace App\Models\Bot;

use App\Models\Bot\Abs\FileAbstract;


class HtmlFileBot extends FileAbstract{
    
    public $filenamehtml;
    public $dir = '/../../../../public/';
    public $data;
    
    public function __construct($data) {
        $this->createFileName();
        $this->generationFiles($data);
    }

    public function createFileName() {
        $this->filenamehtml = 'report_'.date("d-m-Y_H:i:s").'.html';
    }
    
    /*
     * Creating a file with information
     * 
     * @return void
     */
    public function generationFiles($data) {
        
        if(!file_exists($this->filenamehtml)) {
            file_put_contents(__DIR__.$this->dir.$this->filenamehtml, '');
        }
        
//        $this->data[] = ['url', 'count', 'H:i:s - ms']; 
        
        foreach ($data as $dom) {
            $this->data[] = [ 'url' => $dom->url, 'count' => $dom->countImg, 'timeOute' => $this->getTimeLoad($dom->end, $dom->start) ];
        }
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getFilename() {
        return $this->filenamehtml;
    }
    
}
