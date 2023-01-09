<?php

class User extends Database
{
    public function all($limit=null)
    {
        return $this->fetchAll("SELECT * FROM users ORDER BY id ASC");
    }
    public function get($param)
    {

      return  $this->fetch( "SELECT * FROM `users` WHERE `email`=?",
      [$param]);
    }
    public function getById($param)
    {

      return  $this->fetch( "SELECT * FROM `users` WHERE `id`=?",
      [$param]);
    }
    public function checkEmailExist($email){
      if (is_array($this->get($email))) 
        return true;
    }
    public function add ($name, $email,$password) {
      return $this->runQuery(
        "INSERT INTO `users` (`name`, `email`,`password`) VALUES (?, ?, ?)",
        [$name, $email, $password]
      );
    }
}