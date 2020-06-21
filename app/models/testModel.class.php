<?php

class testModel extends model{
    protected $tablename = "test";
    
    public $id;
	public $name;
    
    public function __construct($tablename = false){
        if(!empty($tablename)){
            $this->tablename = $tablename;
        }
        parent::__construct($this->tablename);
    }

    public function insertar(){
        $lista = array();
        if(
			empty($this->name)
		){
			return false;
		}
		$lista = array(
			'name' => $this->name
		);
		return $this->insert($lista);
	}
	public function multipleInsert($arr){
		if(empty($arr)){
			throw new Exception("Array is empty");
		}
		try{
			$this->iniciarTransaccion();
			$this->insertvalues($arr);
			$this->terminarTransaccion();
		}catch(Exception $e){
			$this->cancelarTransaccion();
			throw new Exception($e->getMessage());
		}
		return true;
	}
	public function multipleUpdate($arr){
		if(empty($arr)){
			throw new Exception("Array is empty");
		}
		try{
			$this->iniciarTransaccion();
			$this->updatevalues($arr);
			$this->terminarTransaccion();
		}catch(Exception $e){
			$this->cancelarTransaccion();
			throw new Exception($e->getMessage());
		}
		return true;
	}
}

?>