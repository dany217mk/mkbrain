<?php
class Method extends Model{
  public function getAll(){
    $query = "SELECT * FROM `methods`";
    return $this->returnAllNum($query);
  }
}
