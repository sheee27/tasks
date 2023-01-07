<?php

class Database{
    
    private $db_host = 'localhost';
    private $db_name = 'tasks';
    private $db_username = 'root';
    private $db_password = '';

    public $conn = null;
    public $statement = null; 
    public $error = null;  

    function __construct(){

    	try{
		   $this->conn =  new PDO('mysql:host='.$this->db_host,$this->db_username,$this->db_password);

		   $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		   $this->conn->query("CREATE DATABASE IF NOT EXISTS ".$this->db_name);
           $this->conn->query("use ".$this->db_name);
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
    }

  
  public function runQuery($sql, $data=null) {
    $this->statement = $this->conn->prepare($sql);
    $this->statement->execute($data);
  }

  public function findById($sql, $data=null) {
    $this->runQuery($sql, $data);
    return $this->statement->fetch();
  }

  
  public function all($sql, $data=null, $arrange=null) {

    $this->runQuery($sql, $data);

   
    if ($arrange===null) { return $this->statement->fetchAll(); }

    else if (is_string($arrange)) {
      $data = [];
      while ($r = $this->statement->fetch()) { $data[$r[$arrange]] = $row; }
      return $data;
    }

    else {
      $data = [];
      while ($r = $this->statement->fetch()) { $data[$r[$arrange[0]]] = $r[$arrange[1]]; }
      return $data;
    }
  }
    
   
}