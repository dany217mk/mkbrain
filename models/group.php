<?php
class Group extends Model{
  public function getCount(){
    $query = "SELECT COUNT(*) FROM `groups` LEFT JOIN `requests` ON `request_group_id` = `group_id` WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_status_id` = 2";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function getAllCount(){
    $query = "SELECT COUNT(*) FROM `groups`  LEFT JOIN `users` ON `user_id` = '" . $_COOKIE['uid'] . "' WHERE `user_organization_id` = `group_organization_id`";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function countRequests(){
    $query = "SELECT COUNT(*) FROM `requests` WHERE `request_user_id` = '" . $_COOKIE['uid'] . "'";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function delete($id){
    $query = "DELETE FROM `requests` WHERE `request_id` = '" . $id . "'";
    return $this->returnActionQuery($query);
  }

  public function add($id){
    $query = "INSERT INTO `requests` (`request_user_id`, `request_group_id`, `request_status_id`) values ('" . $_COOKIE['uid'] . "', '$id', 1)";
    return $this->returnActionQuery($query);
  }

  public function checkIsMyGroup($id){
    $query = "SELECT COUNT(*) FROM `requests`
              WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_group_id` = '$id'";
    $row = $this->returnAssoc($query);
    if ($row['COUNT(*)'] > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function getViewGroup($id){
    $query = "SELECT *, `organization_name`, `user_name`, `user_surname`, `user_id` FROM `groups`
              LEFT JOIN `users` ON `user_id` = `group_user_id`
              LEFT JOIN `organizations` ON `organization_id` = `group_organization_id`
              WHERE `group_id` = '$id'";
    return $this->returnAssoc($query);
  }

  public function getUsersCount($id){
    $query = "SELECT COUNT(*) FROM `requests`
              WHERE `request_group_id` = '$id'";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }
  public function checkIfUserAdmin($id){
    $query = "SELECT COUNT(*) FROM `groups`
              WHERE `group_user_id` = '" . $_COOKIE['uid'] . "' AND `group_id` = '$id'";
    $row = $this->returnAssoc($query);
    if ($row['COUNT(*)'] > 0) {
      return true;
    } else {
      return false;
    }
  }


}
