<?php

namespace App;

use App\View;
use App\BaseBuilder;
use App\Container;

class App
{
    protected $config;
    protected $builder;
    protected $container;
    public $response;
    public $request;
    public $router;
    public $view;

    public function __construct($config) {
        $this->config = $config;
        $this->builder = $this->configureBuilder(new BaseBuilder());
        $this->container = new Container();
    }
    
    protected function configureBuilder(BaseBuilder $builder)
    {
        $view = new View();
        $view->setTemplatePath(__DIR__.'/../templates');
        
        $definitions = [
            'view' => $view,    
        ];
        
        $builder->addDefinitions($this, array_merge($definitions, $this->config));
    }
    
    public function init() {
        $this->response = $this->container->get('response');
        $this->request = $this->container->get('request');
        $this->response->set($this->request);
        $this->router = $this->container->get('router');
    }
    
    public function run() {

        try {
            $output = $this->process($this->request, $this->response);
        } catch (InvalidMethodException $e) {
            $response = $e->getRequest();
        } 

        if(!empty($this->response->errors[0])) {
            return implode("\n", $this->response->errors);
        }

        return $this->router->run();
    }
    
    public function process($request, $response) {
        
        if (is_callable([$request, 'getBasePath']) && is_callable([$this->router, 'setBasePath'])) {
            if(!$this->router->getBasePath()) {
                $this->router->setBasePath($request->getBasePath($response));
            }
        }
    }
}
