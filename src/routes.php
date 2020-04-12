<?php

// Custom 404 Handler
$app->router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});

$app->router->setNamespace('\App\Controllers');

$app->router->get('/', ['HomeController@index']);
$app->router->post('/', ['HomeController@store']);

$app->router->get('/table/{scheme}/host/{host1}/{host2}', ['HomeController@table']);
