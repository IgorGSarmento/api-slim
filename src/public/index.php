<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
include '../settings/settings.php';

$app = new \Slim\App(["settings" => $config]);

//Handle Dependencies
$container = $app->getContainer();

$container['db'] = function ($c) {
   
   try{
       $db = $c['settings']['db'];
       $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
       PDO::ATTR_DEFAULT_FETCH_MODE                      => PDO::FETCH_ASSOC,
       );
       $pdo = new PDO("mysql:host=" . $db['servername'] . ";port=" . $db['port'] . ";dbname=" . $db['dbname'],
       $db['username'], $db['password'],$options);
       return $pdo;
   }
   catch(\Exception $ex){
       return $ex->getMessage();
   }
   
};

// Registra componete de template no container
$container['view'] = function ($container) {
    return new \Slim\Views\PhpRenderer('../templates');
};


// Rota padrão com template
$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'default.php', 
    	array("data"=>array("Hello"=>"World!"))
    );
    
})->setName('profile');

// Adiciona usuario
$app->post('/user', function (Request $request, Response $response, array $args) {
   
   try{
       $con = $this->db;
       var_dump($con);
       $sql = "INSERT INTO `usuarios`(`usuario`, `email`,`senha`) VALUES (:username,:email,:password)";
       $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':username' => $request->getParam('usuario'),
       ':email' => $request->getParam('email'),
		//Using hash for password encryption
       ':password' => password_hash($request->getParam('senha'),PASSWORD_DEFAULT)
       );
       $result = $pre->execute($values);
       return $response->withJson(array('status' => 'Usuãrio criado!'),200);
       
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

// Lista usuario pelo id
$app->get('/user/{id}', function ($request,$response) {
   try{
       $id     = $request->getAttribute('id');
       $con = $this->db;
       $sql = "SELECT * FROM usuarios WHERE id = :id";
       $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':id' => $id);
       $pre->execute($values);
       $result = $pre->fetch();
       if($result){
           return $response->withJson(array('status' => 'true','result'=> $result),200);
       }else{
           return $response->withJson(array('status' => 'Usuário não encontrado!'),422);
       }
      
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

// Lista todos usuarios
$app->get('/users', function ($request,$response) {
   try{
       $con = $this->db;
       $sql = "SELECT * FROM usuarios";
       $result = null;
       foreach ($con->query($sql) as $row) {
           $result[] = $row;
       }
       if($result){
           return $response->withJson(array('status' => 'true','result'=>$result),200);
       }else{
           return $response->withJson(array('status' => 'Usuários não encontrados!'),422);
       }
              
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

// Edita usuario
$app->put('/user/{id}', function ($request,$response) {
   try{
       $id     = $request->getAttribute('id');
       $con = $this->db;
       $sql = "UPDATE usuarios SET usuario=:name,email=:email,senha=:password WHERE id = :id";
       $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':name' => $request->getParam('usuario'),
       ':email' => $request->getParam('email'),
       ':password' => password_hash($request->getParam('senha'),PASSWORD_DEFAULT),
       ':id' => $id
       );
       $result =  $pre->execute($values);
       if($result){
           return $response->withJson(array('status' => 'Usuário atualizado!'),200);
       }else{
           return $response->withJson(array('status' => 'Usuário não encontrado!'),422);
       }
              
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

// Deleta usuario
$app->delete('/user/{id}', function ($request,$response) {
   try{
       $id     = $request->getAttribute('id');
       $con = $this->db;
       $sql = "DELETE FROM usuarios WHERE id = :id";
       $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':id' => $id);
       $result = $pre->execute($values);
       if($result){
           return $response->withJson(array('status' => 'Usuário Deletado!'),200);
       }else{
           return $response->withJson(array('status' => 'Usuário não encontrado!'),422);
       }
      
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});


$app->run();




