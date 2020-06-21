<?php
/**
 * Query Class for building SQL with methods or direct query
 */
class query{
    protected $myTable;

    public __construct($table = null){
        $this->myTable = $table;
    }

    public static table($table){

    }
    /**
     * Return the result of building query
     * @return Array assoc 
     */
    public function get(){
        //do it
    }
    /**
     * Return the result of building query
     * @return Object assoc 
     */
    public function getObj(){
        //do it
    }
    public function first(){
        //do it
    }
    public function select(){
        //do it
    }
    public function where(){
        //do it
    }
    public function update(){
        //do it
    }
    public function insert(){
        //do it
    }
    public function delete(){
        //do it
    }
    public function selectRaw(){
        //do it
    }
    public function selectRaw(){
        //do it
    }
    /**
     * Direct query 
     * @param String sql query
     * @return Array assoc
     */
    public function query(){
        //do it
    }
    /**
     * Get and setter
     */

    public function setTable(){

    }
}
?>