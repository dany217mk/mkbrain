<?php
class Friend extends Model{
  public function getCount(){
    $query = "SELECT COUNT(*) FROM `friends` WHERE (`friend_recipient_id` = '" . $_COOKIE['uid'] . "' OR `friend_sender_id` = '" . $_COOKIE['uid'] . "') AND `friend_status_id` = 2;";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function delete($id){
    $query = "DELETE FROM `friends` WHERE `friend_id` = '" . $id . "'";
    return $this->returnActionQuery($query);
  }

  public function add($id){
    $query = "INSERT INTO `friends` (`friend_sender_id`, `friend_recipient_id`, `friend_status_id`) values ('" . $_COOKIE['uid'] . "', '$id', 1)";
    return $this->returnActionQuery($query);
  }

  public function accept($id){
    $query = "UPDATE `friends` SET `friend_status_id` = 2 WHERE `friend_sender_id` = '" . $id . "' AND `friend_recipient_id` = '" . $_COOKIE['uid'] . "'";
    return $this->returnActionQuery($query);
  }

  public function acceptFriendId($id){
    $query = "UPDATE `friends` SET `friend_status_id` = 2 WHERE `friend_id` = '" . $id . "';";
    return $this->returnActionQuery($query);
  }

  public function countFriends(){
    $query = "SELECT COUNT(*) FROM `friends`"
                  . " WHERE (`friend_recipient_id` = '" . $_COOKIE['uid'] . "' OR"
                  . " `friend_sender_id` = '" . $_COOKIE['uid'] . "') AND `friend_status_id` = 2;";
     return $this->returnAssoc($query);
  }

  public function getUserTests($id){
    $query = "SELECT `test_id`, `test_name`, `test_code` FROM `tests` WHERE `test_user_id` = '$id' AND `test_privacy` = 0;";
    return $this->returnAllAssoc($query);
  }

  public function countAllUsers(){
    $query = "SELECT COUNT(*) FROM `users`";
     return $this->returnAssoc($query)['COUNT(*)'];
  }

  public function checkIfUserFriend($id){
    $query = "SELECT * FROM `friends` WHERE (`friend_sender_id` = '" . $_COOKIE['uid'] . "' AND `friend_recipient_id` = '" . $id . "')
     OR (`friend_sender_id` = '" . $id . "' AND `friend_recipient_id` = '" . $_COOKIE['uid'] . "')";
    return $this->returnNumRows($query);
  }

  public function countViewFriends($id){
    $query = "SELECT * FROM `friends` WHERE (`friend_sender_id` = '" . $_COOKIE['uid'] . "' AND `friend_recipient_id` = $id) OR (`friend_sender_id` = $id AND `friend_recipient_id` = '" . $_COOKIE['uid'] . "')";
    return $this->returnActionQuery($query);
  }

  public function existViewImg($id){
   if (is_null($this->getViewUser($id)['user_img']) || $this->getViewUser($id)['user_img'] == "") {
      return IMG_DEFAULT;
  } else {
     return '<img src="' . IMG_USER . '/' . $this->getViewUser($id)['user_img'] . '">';
  }
  }

  public function getViewUser($id){
    $query = "SELECT *, `role_name`, `gender_name`, `organization_name` FROM `users`
    LEFT JOIN `roles` ON `role_id` = `user_role_id`
    LEFT JOIN  `genders` ON `gender_id` = `user_gender_id`
    LEFT JOIN `organizations` ON `organization_id` = `user_organization_id`
     WHERE `user_id` = '" . $id . "'";
    return $this->returnAssoc($query);
  }


  public function countRequests(){
    $query = "SELECT COUNT(*) FROM friends WHERE friend_recipient_id = '" . $_COOKIE['uid'] . "' AND friend_status_id = '1';";
     return $this->returnAssoc($query)['COUNT(*)'];
  }

  public function countMyRequests(){
    $query = "SELECT COUNT(*) FROM `friends` WHERE `friend_sender_id` = '" . $_COOKIE['uid'] . "' AND friend_status_id = '1';";
     return $this->returnAssoc($query)['COUNT(*)'];
  }
}
