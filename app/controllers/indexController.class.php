<?php

class indexController extends controller{

	public function __construct(){
		parent::__CONSTRUCT();
	}

	public function indexAction(){
		try{
			$this->render();
		}catch(Exception $e){
			return $e->getMessage();
		}
	}
}
?>