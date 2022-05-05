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

  public function countRecords($id){
    $query = "SELECT COUNT(*) FROM `records`  WHERE `record_group_id` = $id";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function getRecordImgs($id){
    $query = "SELECT * FROM `records_img` WHERE `record_img_record_id` = '$id'";
    return $this->returnAllAssoc($query);
  }

  public function getCountMyOrgGroup($org){
    $query = "SELECT COUNT(*) FROM `groups` WHERE `group_organization_id` = '$org'";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function addRecord($text, $type, $id, $doc){
    $query = "INSERT INTO `records`(`record_group_id`, `record_user_id`, `record_type_id`, `record_date`, `record_text`) VALUES ('$id','" . $_COOKIE['uid'] . "','$type',FROM_UNIXTIME('$doc'),'$text')";
    return $this->returnLastId($query);
  }

  public function addRecordImg($id, $filename){
    $query = "INSERT INTO `records_img`(`record_img_record_id`, `record_img_path`) VALUES ('$id','$filename')";
    return $this->actionQuery($query);
  }

  public function existImg($id_group){
    if (is_null($this->getImg($id_group)) || $this->getImg($id_group) == "") {
       return IMG_GROUP_DEFAULT;
    } else {
        return '<img src="' . IMG_GROUP . '/' . $this->getImg($id_group) . '">';
     }
  }

  public function countParticipants($id_group){
    $query = "SELECT COUNT(*) FROM `requests` WHERE `request_group_id` = '$id_group' AND `request_status_id` = 2";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function accessParticipant($id){
    $query = "UPDATE `requests` SET `request_status_id` = '2' WHERE `request_id` = '$id';";
    return $this->returnActionQuery($query);
  }

  public function countParticipantsRequests($id_group){
    $query = "SELECT COUNT(*) FROM `requests` WHERE `request_group_id` = '$id_group' AND `request_status_id` = 1";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function deleteParticipant($id){
    $query = "DELETE FROM `requests` WHERE `request_id` = '" . $id . "'";
    return $this->returnActionQuery($query);
  }

  public function getImg($id){
    $query = "SELECT `group_img` FROM `groups` WHERE `group_id` = '$id'";
    $res = $this->returnAssoc($query);
    return $res['group_img'];
  }

  public function updateAllow($allow, $id){
    $query = "UPDATE groups SET group_allow_add = '$allow' WHERE group_id = '$id'";
     return $this->returnActionQuery($query);
  }

  public function updateImg($filename, $id){
    $query = "UPDATE `groups` SET `group_img` = '" . $filename . "' WHERE `group_id` = '$id'";
    return $this->returnActionQuery($query);
  }

  public function deleteimg($id){
    $query = "UPDATE `groups` SET `group_img` = '' WHERE `group_id` = '$id'";
    $this->actionQuery($query);
  }

  public function addImgGroup(){
    $upload_path = IMG_GROUP . '/';
    $filename = $this->getHelper()->generationToken() . ".png";

    $allow = false;
    $counterIter = 0;

    while (!$allow) {
      $counterIter++;
      $fetch_mas = $this->check_group_img($filename);
      $counter =  $fetch_mas['COUNT(*)'];
      if ($counter > 0) {
        $allow = false;
      } else {
        $allow = true;
      }
    if (!$allow){
      $filename = $this->getHelper()->generationToken() . ".png";
    }
  }
    return $filename;
  }

  public function check_group_img($filename){
    $query = "SELECT COUNT(*) FROM `groups` WHERE `group_img` = '" . $filename . "'";
    $res = $this->returnActionQuery($query);
    return mysqli_fetch_assoc($res);
  }

  public function getCountTeacherGroups(){
    $query = "SELECT COUNT(*) FROM `groups` WHERE `group_user_id` = '" . $_COOKIE['uid'] . "'";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function addGroup($name, $describe, $filename, $allow, $code, $doc, $org){
    $query = "INSERT INTO `groups`(`group_name`, `group_user_id`, `group_describe`, `group_img`, `group_code`, `group_organization_id`, `group_doc`, `group_allow_add`) VALUES ('$name','" . $_COOKIE['uid'] . "','$describe','$filename','$code','$org',FROM_UNIXTIME('$doc'),'$allow')";
    $last_id = $this->returnLastId($query);
    $query2 = "INSERT INTO `requests`(`request_user_id`, `request_group_id`, `request_status_id`) VALUES ('" . $_COOKIE['uid'] . "','$last_id','2')";
    $this->actionQuery($query2);
    return $last_id;
  }

  public function getCountAllRecords(){
    $query = "SELECT COUNT(*) FROM `records`
              LEFT JOIN `groups` ON `record_group_id` = `group_id`
              LEFT JOIN `requests` ON `request_group_id` = `group_id`
              WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_status_id` = 2";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function addImg(){
    $upload_path = IMG_RECORD . '/';
    $filename = $this->getHelper()->generationToken() . ".png";

    $allow = false;
    $counterIter = 0;

    while (!$allow) {
      $counterIter++;
      $fetch_mas = $this->check_record_img($filename);
      $counter =  $fetch_mas['COUNT(*)'];
      if ($counter > 0) {
        $allow = false;
      } else {
        $allow = true;
      }
    if (!$allow){
      $filename = $this->getHelper()->generationToken() . ".png";
    }
  }
    return $filename;
  }

  public function check_record_img($filename){
    $query = "SELECT COUNT(*) FROM `records_img` WHERE `record_img_path` = '" . $filename . "'";
    $res = $this->returnActionQuery($query);
    return mysqli_fetch_assoc($res);
  }

  public function checkIfPutLike($id){
    $query = "SELECT COUNT(*) FROM `record_likes` WHERE `record_like_user_id` = '" . $_COOKIE['uid'] . "' AND `record_like_record_id` = '$id'";
    $row = $this->returnAssoc($query);
    if ($row['COUNT(*)'] > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function checkIfIsAdmin($id){
    $query = "SELECT COUNT(*) FROM `groups` LEFT JOIN `requests` ON `request_id` = '$id' WHERE `group_user_id` = '" . $_COOKIE['uid'] . "' AND `group_id` = `request_group_id`";
    $row = $this->returnAssoc($query);
    if ($row['COUNT(*)'] > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function checkIfRecordMy($id){
    $queryCheck = "SELECT COUNT(*) FROM `records` WHERE `record_user_id` = '" . $_COOKIE['uid'] . "' AND `record_id` =  $id";
    $row = $this->returnAssoc($queryCheck);
    if ($row['COUNT(*)'] > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function deleteRecord($id){
    $query = "SELECT * FROM `records_img` WHERE `record_img_record_id` = $id";
    $dataImg = $this->returnAllAssoc($query);
    if (!empty($dataImg)) {
      foreach ($dataImg as $item) {
        unlink('./assets/img_record/' . $item['record_img_path']);
      }
    }
    $queryDelImg = "DELETE FROM `records_img` WHERE `record_img_record_id` = $id";
    $queryDelLikes = "DELETE FROM `record_likes` WHERE `record_like_record_id` = $id";
    $queryDelComment = "DELETE FROM `comments` WHERE `comment_record_id` = $id";
    $queryDel = "DELETE FROM `records` WHERE `record_id` = $id";
    $this->actionQuery($queryDelImg);
    $this->actionQuery($queryDelLikes);
    $this->actionQuery($queryDelComment);
    return $this->returnActionQuery($queryDel);
  }

  public function putLike($id){
    $query = "INSERT INTO `record_likes`(`record_like_user_id`, `record_like_record_id`) VALUES ('" . $_COOKIE['uid'] . "','$id')";
    return $this->actionQuery($query);
  }

  public function removeLike($id){
    $query = "DELETE FROM `record_likes` WHERE `record_like_user_id` = '" . $_COOKIE['uid'] . "' AND `record_like_record_id` = '$id'";
    return $this->actionQuery($query);
  }

  public function getCountLikes($id){
    $query = "SELECT COUNT(*) FROM `record_likes` WHERE `record_like_record_id` = '$id'";
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

  public function addAccess($id){
    $query = "INSERT INTO `requests` (`request_user_id`, `request_group_id`, `request_status_id`) values ('" . $_COOKIE['uid'] . "', '$id', 2)";
    return $this->returnActionQuery($query);
  }

  public function update($id){
    $query = "UPDATE `requests` SET `request_status_id`='2' WHERE `request_id` = '$id'";
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

  public function getComments($id){
    $query = "SELECT `user_name`, `user_surname`, `comment_text`, `comment_id`, `user_img`, `user_id`, `comment_date` FROM `comments` LEFT JOIN `users` ON `user_id` = `comment_user_id` WHERE `comment_record_id` = '$id';";
    return $this->returnAllAssoc($query);
  }

  public function addComment($id, $text, $date){
    $query = "INSERT INTO `comments`(`comment_text`, `comment_user_id`, `comment_record_id`, `comment_date`) VALUES ('$text','" . $_COOKIE['uid'] . "','$id',FROM_UNIXTIME('$date'))";
    return $this->returnActionQuery($query);
  }

  public function getUserRequest($id){
    $query = "SELECT * FROM `requests` WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_group_id` = $id;";
    return $this->returnActionQuery($query);
  }

  public function checkIfUserParticipant($id){
    $query = "SELECT COUNT(*) FROM `requests`
              WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_group_id` = '$id' AND `request_status_id` = 2";
    $row = $this->returnAssoc($query);
    if ($row['COUNT(*)'] > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function checkIfUserRequest($id){
    $query = "SELECT `request_id` FROM `requests`
              WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_group_id` = '$id'";
    $row = $this->returnAssoc($query);
    if (empty($row)) {
      return 0;
    }
    return $row['request_id'];
  }

  public function getAllTypes(){
    $query = "SELECT * FROM `types`;";
    return $this->returnAllNum($query);
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
