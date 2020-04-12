<?php
namespace App\Models\Bot\Abs;

abstract class FileAbstract {

    abstract function createFileName();
    abstract function generationFiles($data);
    abstract function getFilename();


    /*
     * redicrect filename
     * 
     * @return void
     */
    public function openFile() {
        if(file_exists(__DIR__.'/../../../../../public/'.$this->getFilename())) {
            header('Location: /'.$this->getFilename());
            exit;
        }
    }
    
    /*
     * Time spent
     * 
     * @return H:i:s - ms
     */

    public function getTimeLoad($end, $start) {
        $res = $this->secToArray($end - $start);
        return $res['hours'].':'.$res['minutes'].':'.$res['secs'].' - '.($end - $start);
    }
    
    /*
     * Calculates hours, minutes, and seconds
     * 
     * @return array
     */
    private function secToArray($secs) {
        $res = array();

        $res['days'] = floor($secs / 86400);
        $secs = $secs % 86400;

        $res['hours'] = floor($secs / 3600);
        $secs = $secs % 3600;

        $res['minutes'] = floor($secs / 60);
        $res['secs'] = $secs % 60;

        return $res;
    }
    
}
