<?php
class Message extends Model{
  public function getLastMsg($data, $i){
    $query = "SELECT `msg`, `msg_sender_id`, `msg_id`, `msg_status` FROM `messages` WHERE
             (`msg_sender_id` = '" . $_COOKIE['uid'] . "' AND `msg_recipient_id` = '" . $data[$i]['user_id'] . "') OR
             (`msg_sender_id` = '" . $data[$i]['user_id'] . "' AND `msg_recipient_id` = '" . $_COOKIE['uid'] . "')
              ORDER BY `msg_id` DESC LIMIT 1";
    return $this->returnActionQuery($query);
  }

  public function add($id, $time_dos, $msg){
    $query = "INSERT INTO `messages`(`msg_sender_id`, `msg_recipient_id`, `msg`, `msg_dos`, `msg_status`) VALUES ('" . $_COOKIE['uid'] ."','" . $id . "','$msg',FROM_UNIXTIME('$time_dos'),1)";
    return $this->returnActionQuery($query);
  }
  public function getAllMsgs($id){
    $query = "SELECT * FROM `messages`
    LEFT JOIN `users` ON `user_id` = '" . $id . "'
    WHERE (`msg_sender_id` = '" . $_COOKIE['uid'] . "' AND `msg_recipient_id` = '" . $id . "') OR
     (`msg_sender_id` = '" . $id . "' AND `msg_recipient_id` = '" . $_COOKIE['uid'] . "') ORDER BY `msg_id`;";
     return $this->returnActionQuery($query);
  }

  public function readAllMsgs($id){
    $query = "UPDATE `messages` SET `msg_status` = 2 WHERE `msg_sender_id` = '" . $id . "' AND `msg_recipient_id` = '" . $_COOKIE['uid'] . "'";
    $this->actionQuery($query);
  }
}
