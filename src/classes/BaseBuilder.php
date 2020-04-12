<?php

namespace App;

class BaseBuilder {
    
    public $definitions= [];

    public function addDefinitions($app, $definitions) {
        $this->definitions = $definitions;
        foreach ($this->definitions as $name => $class) {
            $app->{$name} = $class;
        }
    }
    
}
