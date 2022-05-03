<?php
class Organization extends Model{
  public function getCount(){
    $query = "SELECT COUNT(*) FROM `organizations`;";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }
  public function add($name){
    $query = "INSERT INTO `organizations`(`organization_name`, `organization_user_id`) VALUES ('$name','" . $_COOKIE['uid'] . "')";
    $last_id = $this->returnLastId($query);
    $query = "UPDATE `users` SET `user_organization_id` = $last_id WHERE `user_id` = '" . $_COOKIE['uid'] . "';";
    $this->actionQuery($query);
  }
}
