<?php

class Database{

    public $conn = null;
    public $statement = null; 
    public $error = null;  

    function __construct(){

    	try{
		   $this->conn =  new PDO('mysql:host='.DB_HOST,DB_USERNAME,DB_PASSWORD);

		   $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		   $this->conn->query("CREATE DATABASE IF NOT EXISTS ".DB_DATABASE_NAME);
           $this->conn->query("use ".DB_DATABASE_NAME);

           $this->createTables($this->conn);
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
    }
    public function createTables(){

      $statements = [
      "CREATE TABLE IF NOT EXISTS tasks( 
            task_id INT AUTO_INCREMENT,
            subject VARCHAR(100) NOT NULL, 
            description  TEXT NULL, 
            status ENUM('New', 'Incomplete', 'Complete'),
            priority ENUM('Low', 'Medium', 'High') NOT NULL,
            start_date timestamp NULL,
            due_date timestamp NULL,
            PRIMARY KEY(task_id)
        );",

        "CREATE TABLE IF NOT EXISTS `users` (
           `id` int(11) NOT NULL AUTO_INCREMENT,
           `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
           `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
           `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
           PRIMARY KEY (`id`),
           UNIQUE KEY `email` (`email`)
          );"
        ,
      'CREATE TABLE IF NOT EXISTS notes (
            note_id   INT NOT NULL AUTO_INCREMENT, 
            task_id INT NOT NULL, 
            subject   VARCHAR(100) NOT NULL,
            attachment VARCHAR(100) NOT NULL,
            note TEXT NOT NULL,
            PRIMARY KEY(note_id),
             FOREIGN KEY (task_id) REFERENCES tasks(task_id)
        )'];

        foreach ($statements as $statement) {
            $this->conn->exec($statement);
          }
    }
  
  public function runQuery($sql, $params = []) {

    try{
        $this->statement = $this->conn->prepare($sql);

      $this->statement->execute($params);
    }
    catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
  }
  
  public function fetch ($sql, $data=null) {
    $this->runQuery($sql, $data);
    return $this->statement->fetch();
  }

  public function lastInsertedId(){

    return $this->conn->lastInsertId();
  }

  
   public function fetchAll($sql = "" , $params = [])
    { 
        try {
           $this->runQuery( $sql , $params );
            return $this->statement->fetchAll();
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }
    
   
}