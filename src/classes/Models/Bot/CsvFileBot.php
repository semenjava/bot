<?php
namespace App\Models\Bot;

use App\Models\Bot\Abs\FileAbstract;


class CsvFileBot extends FileAbstract
{
    private $filenamecsv;
    
    public function __construct($data) {
        $this->createFileName();
        $this->generationFiles($data);
    }

    public function createFileName() {
        $this->filenamecsv = 'report_'.date("d-m-Y_H:i:s").'.csv';
    }
    
    
    /*
     * Creating a file with information
     * 
     * @return void
     */
    public function generationFiles($data) {
        if(!file_exists($this->filenamecsv)) {
            file_put_contents(__DIR__.$this->dir.$this->filenamecsv, '');
        }
        
        $fp = fopen(__DIR__.$this->dir.$this->filenamecsv, 'w+');
        fputcsv($fp, ['url', 'count', 'H:i:s  - ms']);
        foreach ($data as $dom) {
            fputcsv($fp, [ $dom->url, $dom->countImg, $this->getTimeLoad($dom->end, $dom->start) ]);
        }
        fclose($fp);
    }
    
    public function getFilename() {
        return $this->filenamecsv;
    }
}
