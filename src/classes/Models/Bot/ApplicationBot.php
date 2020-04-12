<?php

namespace App\Models\Bot;

use App\Models\Bot\Int\ParseInterface;
use App\Models\Bot\LinkBot;
use App\Models\Bot\HrefBot;
use App\Models\Bot\SrcBot;
use App\Models\Bot\Helper\BotUrlHelper;

class ApplicationBot {

    public $parse;
    public $links;
    public $data;
    public $deep;

    public function __construct(ParseInterface $parse, $deep = 0) {
        $this->setParse($parse);
        $this->deep = $deep;
    }

    public function setParse($parse) {
        $this->parse = $parse;
    }
    
    public function execute() {
        $this->parse->createDom();
        $this->maping();
        return $this->getData();
    }
    
    private function getData() {
        return $this->data;
    }

    /*
     * Collection of real links
     * 
     * @return void
     */

    public function maping() {

        if(count($this->parse->domHtml->find('a'))){
            foreach ($this->parse->domHtml->find('a') as $element) {
                $link = new LinkBot(new HrefBot($element->href));

                if(!empty($link->link->getParseUrl()['host'])) {
                    $host = $link->link->getParseUrl()['host'];
                } else {
                    $host = false;
                }

                if (BotUrlHelper::isSameSite($host, $this->parse->request->getHost()) &&
                        !BotUrlHelper::isRepeat($element->href, $this->parse->request->getUrls())) {

                    //start connect
                    $this->parse->request->setConnectTimeout(time());
                    $link->startConnect($this->parse->request->getConnectTimeout());

                    $countImg  = 0;
                    if (BotUrlHelper::url_exists($link->link->getLink())) {
                        $countImg = count($this->parse->domHtml->getImgs());
                    }

                    //end connect
                    $this->parse->request->setTimeout(time());
                    $link->endConnect($this->parse->request->getTimeout());

                    $this->parse->request->addUrl($element->href);

                    $this->data[] = (object)[
                        'url' => $link->link->getLink(),
                        'countImg' => $countImg,
                        'end' => $link->end,
                        'start' => $link->start
                    ];
                }
                unset($link);
            }
        }

        foreach ($this->parse->request->getUrls() as $url) {
            $this->analise($url);
        }
        
    }

    /*
     * Link Analysis with Depth
     * 
     * @return void
     */

    public function analise($url, $level = 0) {
        if ($this->deep > $level  && BotUrlHelper::url_exists($url)) {
            $dom = $this->parse->createDomByLink($url);

            if(count($dom->find('a'))){
                foreach ($dom->find('a') as $element) {
                    $link = new LinkBot(new HrefBot($element->href));

                    if(!empty($link->link->getParseUrl()['host'])) {
                        $host = $link->link->getParseUrl()['host'];
                    } else {
                        $host = false;
                    }

                    if (BotUrlHelper::isSameSite($host, $this->parse->request->getHost()) &&
                            !BotUrlHelper::isRepeat($element->href, $this->parse->request->getUrls())) {

                        //start connect
                        $this->parse->request->setConnectTimeout(time());
                        $link->startConnect($this->parse->request->getConnectTimeout());

                        $countImg  = 0;
                        if (BotUrlHelper::url_exists($link->link->getLink())) {
                            $countImg = count($this->parse->domHtml->getImgs());
                        }

                        //end connect
                        $this->parse->request->setTimeout(time());
                        $link->endConnect($this->parse->request->getTimeout());

                        $this->parse->request->addUrl($element->href);
                        $this->data[] = (object)[
                            'url' => $link->link->getLink(),
                            'countImg' => $countImg,
                            'end' => $link->end,
                            'start' => $link->start
                        ];
                        $this->analise($element->href, $level++);
                    }
                    unset($link);
                }
                unset($dom);
            }
        }
    }
}
