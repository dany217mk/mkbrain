<?php
class Gender extends Model{
  public function getAll(){
    $query = "SELECT * FROM `genders` ORDER BY `gender_name`";
    return $this->returnAllNum($query);
  }
}
