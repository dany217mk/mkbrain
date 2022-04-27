<?php
class Subject extends Model{
  public function getAll(){
    $query = "SELECT * FROM `subjects`";
    return $this->returnAllNum($query);
  }
}
