<?php

class Task {

   private $db;

   public function __construct($db)
    {
        $this->db = $db;
    }
 
  public function getAll () {
    return $this->db->all(
      "SELECT * FROM `tasks`",
      null, "id"
    );
  }

  public function get ($id) {
    return $this->db->findById(
      "SELECT * FROM `tasks` WHERE `user_". (is_numeric($id)?"id":"email") ."`=?",
      [$id]
    );
  }

  public function add ($subject) {
    return $this->db->runQuery(
      "INSERT INTO `tasks` (`subject`) VALUES (?)",
      [$subject]
    );
  }

  public function edit ($name, $email, $id) {
    return $this->db->runQuery(
      "UPDATE `tasks` SET `user_name`=?, `user_email`=? WHERE `user_id`=?",
      [$name, $email, $id]
    );
  }


  public function del ($id) {
    return $this->db->runQuery(
      "DELETE FROM `tasks` WHERE `id`=?",
      [$id]
    );
  }
}