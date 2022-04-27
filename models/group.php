<?php
class Group extends Model{
  public function getCount(){
    $query = "SELECT COUNT(*) FROM `groups` LEFT JOIN `requests` ON `request_group_id` = `group_id` WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_status_id` = 2";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function countRequests(){
    $query = "";
  }

}
