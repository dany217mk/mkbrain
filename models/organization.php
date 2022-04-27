<?php
class Organization extends Model{
  public function getCount(){
    $query = "SELECT COUNT(*) FROM `organizations`;";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

}
