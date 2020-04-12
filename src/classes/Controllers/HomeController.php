<?php
namespace App\Controllers;

use View\Helper\ViewHelperForm;
use View\Helper\ViewHelperFormTag;
use App\Models\Bot\RequestBot;
use App\Models\Bot\ApplicationBot;
use Http\Response;
use App\Models\Bot\ParseBot;
use View\Helper\ViewHelperTable;
use App\Models\Bot\CsvFileBot;
use App\Models\Bot\HtmlFileBot;

class HomeController extends Controller{
    
    public function index() {
        $view = $this->view();
        $form = new ViewHelperForm($view);
        $formTag = new ViewHelperFormTag($view);
        
        $data = [
            'form'  => [
                'label' => $form->label('post', 'title', 'Введите домен'),
                'textField' => $form->textField('post', 'domen', [
                            'name' => 'domen', 
                            'url' => '/'
                        ]),
                'submit' => $formTag->submitTag('Parse'),
                'startForm' => $formTag->startFormTag('post'),
                'endForm' => $formTag->endFormTag()
            ]
        ];
        
        $view->title = 'Bot parse';
        $view->form = (object)$data['form'];
        
        echo $view->render('table.php');
    }
    
    public function store() {
        $domen = $this->response()->parceUrl($this->request()->input('domen'));
        $host = str_replace('/', '', $domen['host']);
        $host = explode('.', $host);
        $domen['path'] = str_replace('/', '', $domen['path']);
        $path = !empty($domen['path']) ? $domen['path'].'/' : '';
        $this->response()->redirect('/table/'.$domen['scheme'].'/'.$host[0].'/'.$host[1]);
    }
    
    public function table($scheme, $host1, $host2) {
        $urlRequest = new RequestBot($scheme.'://'.$host1.'.'.$host2);
        $urlRequest->setHost($host1.'.'.$host2);
        $bot = new ApplicationBot(new ParseBot($urlRequest));
        $data = $bot->execute(); 

        $csv = new CsvFileBot($data);
        $htm = new HtmlFileBot($data);
        
        $view = $this->view();
        $table = new ViewHelperTable($view);
        
        $content = $table->build_table($htm->getData(), $csv->getFilename());
        file_put_contents(__DIR__.$this->dir.$htm->getFilename(), $content);
        
        $htm->openFile($htm->getFilename());
    }
}
