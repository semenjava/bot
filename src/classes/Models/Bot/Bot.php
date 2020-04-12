<?php

require_once 'botUrl.php';
require_once 'table/class.table.php';

/*
 * Generic Link Processing Class
 */

class Bot {

    /*
     * @var string main domen site
     */
    public $site;
    
    /*
     * @var array list info to generation html file
     */
    public $data;
    
    /*
     * var array list urls to page
     */
    private $urls;
    
    /*
     * @var list obj new botUrl()
     */
    private $mapSites = [];
    
    /*
     * @var string real hhtp_host to main page 
     */
    private $host;
    
    /*
     * @var int deep to next page
     */
    public $deep;

    public function __construct($site, $deep=0) {
        $parse_url = parse_url($site);
        $this->host = $parse_url['host'];
        $this->site = $parse_url['scheme'] . '://' . $parse_url['host'] . '/';
        $this->deep = $deep;
        
        $this->maping();
    }

    /*
     * Collection of real links
     * 
     * @return void
     */
    public function maping() {
        $html = file_get_html($this->site);
        
        foreach ($html->find('a') as $element){ 
            $boturl = new botUrl($element->href);
            if($boturl->isSameSite($this->host) && !$boturl->isRepeat($this->urls)) {
                $boturl->load();
                $this->mapSites[] = $boturl;
                $this->urls[] = $element->href;
            }
            unset($boturl);
        }
        unset($html);

        foreach ($this->mapSites as $url) {
            $this->analise($url, 0);
        }
        
    }

    /*
     * Link Analysis with Depth
     * 
     * @return void
     */
    public function analise($url, $level) {
        if( $level <= $this->deep && botUrl::url_exists($url)) {
            $html = file_get_html($url);
            foreach ($html->find('a') as $element){
                $boturl = new botUrl($element->href);
                if($boturl->isSameSite($this->host) && !$boturl->isRepeat($this->urls)) {
                    $boturl->load();
                    $this->mapSites[] = $boturl;
                    $this->urls[] = $element->href;
                    $this->analise($url, $level++);
                }
                unset($boturl);
            }
            unset($html);
        }
        
    }

    /*
     * Creating a file with information
     * 
     * @return void
     */
    public function generationFiles() {
        
        $filenamecsv = 'report_'.date("d-m-Y_H:i:s").'.csv';
        $filenamehtml = 'report_'.date("d-m-Y_H:i:s").'.html';
        
        if(!file_exists($filenamecsv)) {
            file_put_contents(__DIR__.'/'.$filenamecsv, '');
        }
        
        if(!file_exists($filenamehtml)) {
            file_put_contents(__DIR__.'/'.$filenamehtml, '');
        }
        
//        $this->data[] = ['url', 'count<img>', 'H:i:s - ms']; 
        
        $fp = fopen(__DIR__.'/'.$filenamecsv, 'w+');
        fputcsv($fp, ['url', 'count<img>', 'H:i:s']);
        foreach ($this->mapSites as $map) {
            fputcsv($fp, [ $map->url, $map->countImg, $map->getTimeLoad() ]);
            $this->data[] = [ $map->url, $map->countImg, $map->getTimeLoad() ];
        }
        fclose($fp);
        
        $this->data['tableInfo'] =
        [
            'cols' => [
                ['title' => 'url'], 
                ['title' => 'count img'],
                ['title' => 'H:i:s - ms']
            ],
        ];
        
        $content = Table::html($this->data, $_SERVER['HTTP_REFERER'].$filenamehtml);
        file_put_contents($filenamehtml, $content);
        
        $this->openFile($filenamehtml);
    }

    /*
     * redicrect filename
     * 
     * @return void
     */
    public function openFile($filename) {
        if(file_exists(__DIR__.'/'.$filename)) {
            header('Location: '.$_SERVER['HTTP_REFERER'].$filename);
            exit;
        }
    }

}
