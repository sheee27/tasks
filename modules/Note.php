<?php

class Note extends Database
{
    public function all($limit=null)
    {
        return $this->fetchAll("SELECT * FROM notes ORDER BY note_id ASC");
    }
    
    public function getById($param)
    {

      return  $this->fetch( "SELECT * FROM `notes` WHERE `note_id`=?",
      [$param]);
    }   
    public function add ($data) {
      $insertvalues = array();
      foreach ($data as $d) {
        $questionmarks[] = '(' . $this->placeholder( '?', count($d)) . ')';
        $insertvalues = array_merge( $insertvalues, array_values($d));
      }
      $sql = "INSERT INTO `notes` (`subject`, `task_id`,`attachment`,`note`)  VALUES " . implode( ',', $questionmarks);
       $this->runQuery($sql,$insertvalues);
       return $this->lastInsertedId();
    }

    function placeholder( $text, $count = 0, $separator = ',' ) {
        $result = array();

        if ($count > 0) {
          for ($x = 0; $x < $count; $x++) {
            $result[] = $text;
          }
        }

        return implode( $separator, $result );
      }
}