<?php

class Task extends Database
{
    public function all($limit=null)
    {
        $sql = "SELECT tasks.subject as task_subject , notes.*
         FROM tasks JOIN notes ON tasks.task_id = notes.task_id 
         ORDER BY CASE priority
           WHEN 'High' THEN 1
           WHEN 'Medium' THEN 2
           WHEN 'Low' THEN 3
           ELSE 5
         END";
        return $this->fetchAll($sql);
    }
    
    public function getById($param)
    {

      return  $this->fetch( "SELECT * FROM `tasks` WHERE `task_id`=?",
      [$param]);
    }   
    public function add ($data) {
       $this->runQuery(
        "INSERT INTO `tasks` (`subject`, `description`,`status`,`priority`,`start_date`,`due_date`) VALUES (?, ?, ?, ?, ?, ?)",
        [$data['subject'], $data['description'], $data['status'], $data['priority'], $data['start_date'], $data['due_date']]
      );
       return $this->lastInsertedId();
    }
}