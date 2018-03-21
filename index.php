<?php
require 'vendor/autoload.php';

//instancie o objeto
$app = new \Slim\App();

//defina a rota
$app->get('/', function() {
    $data = array("data"=>array("Hello"=>"World!"));
    return json_encode($data);
});

$app->run();