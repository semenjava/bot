<?php
namespace App;

use Http\Request;
use Http\Response;
use Http\Router;

class Container {

    protected $request;
    protected $response;
    protected $router;

    public function get($name) {
        $className = '\Http\\'.ucfirst($name);
        if(!empty($this->response) && $this->response->getUrl()) {
            $this->{$name} = new $className($this->response->getUrl());
        } else {
            $this->{$name} = new $className();
        } 
        
        return $this->{$name};
    }
}
