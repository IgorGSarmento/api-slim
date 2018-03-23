<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

//defina a rota
$app->get('/', function(Request $request, Response $response, array $args) {
    $data = array("data"=>array("Hello"=>"World!"));
    return json_encode($data);
});

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->get('/pessoas', function(Request $request, Response $response, array $args) {
	$data = array("data"=>array("Listas"=>"Pessoas"));
	return json_encode($data);
});

$app->run();




