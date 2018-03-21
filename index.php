<?php
require 'vendor/autoload.php';

<<<<<<< HEAD
//Instanciando objeto
/*$app = new \Slim\Slim(array(
    'templates.path' => 'templates'
));*/

//instancie o objeto
$app = new \Slim\Slim();

//defina a rota
get('/', function () { 
  echo "Hello, World!"; 
});
=======
//instancie o objeto
$app = new \Slim\App();
>>>>>>> 756554dbad9a6ceddc556d9aff0612afa3ab670c

//defina a rota
$app->get('/', function() {
    $data = array("data"=>array("Hello"=>"World!"));
    return json_encode($data);
});

$app->run();