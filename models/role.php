<?php
class Role extends Model{
  public function getAll(){
    $query = "SELECT * FROM `roles`";
    return $this->returnAllNum($query);
  }
}
