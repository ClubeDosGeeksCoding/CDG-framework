<?php

class CDG{
	public $views;
	public $data = [];
	private $var = [];
	private $body;
	public function get($route, $callback){
		if( $_SERVER['REQUEST_METHOD'] == 'GET'){
			if($this->resolveRoute($route)){
				if(sizeof($this->var)>0){
					extract($this->var);
					eval('$callback('.implode(',', array_map(array($this, 'cifrao'), array_keys($this->var))).');');
				}else{
					$callback();	
				}
				
			}
		}
		
	}
	public function post($route, $callback){
		if( $_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->req($route, $callback);
		}
	}
	public function put($route, $callback){
		if( $_SERVER['REQUEST_METHOD'] == 'PUT'){
			$this->req($route, $callback);
		}
	}

	private function req($route, $callback){
		$this->body = json_decode(file_get_contents('php://input'),TRUE);
		if($this->resolveRoute($route)){
			if(sizeof($this->var)>0){
				extract($this->var);
				eval('$callback('.implode(',', array_map(array($this, 'cifrao'), array_keys($this->var))).');');
			}else{
				$callback();	
			}
		}

	}

	public function render($file, $data){
		$file = $this->views.'/'.$file;
		if(file_exists($file)){
			$data = array_merge($this->data, (array) $data);
	        extract($data);
	        ob_start();
	        require $file;
        	echo ob_get_clean();
		}else{
			echo "O arquivo não existe!";
		}
		exit();
	}

	private function resolveRoute($route){
		$partsRoute = explode('/', $route);
		$partsReq = explode('/', $_SERVER['REQUEST_URI']);
		if(sizeof($partsReq) == sizeof($partsRoute)){
			for($i = 0; $i < sizeof($partsReq) ; $i++){
				if(substr($partsRoute[$i], 0,1) == ":"){
					$this->var[substr($partsRoute[$i], 1)] = $partsReq[$i];
				}else{
					if($partsReq[$i] != $partsRoute[$i]){
						return false;
					}
				}
			}
			return true;
		}else{
			return false;
		}
	}

	private function cifrao($s){
		return '$'.$s;
	}

	public function getBody(){
		return $this->body;
	}
}