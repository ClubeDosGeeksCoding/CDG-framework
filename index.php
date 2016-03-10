<?php 
	require_once("cdg/cdg.php");

	$app = new CDG();

	$app->views = 'views';

	$app->data['title'] = "Meu framework";


	$app->get('/teste/:id/:nome', function($id, $nome, $e = 1) use ($app){
		$app->render('teste.php',['data'=>"teste","id"=>$id, "nome"=>$nome]);
	});

	
	$app->put('/teste/', function() use ($app){
		$body = $app->getBody();
		var_dump($body);
	});
	$app->post('/teste/', function() use ($app){
		$body = $app->getBody();
		var_dump($body);
	});

?>