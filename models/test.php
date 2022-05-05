<?php
class Test extends Model{
  public function getCount(){
    $query = "SELECT COUNT(*) FROM `tests`";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function getCountMyTests(){
    $query = "SELECT COUNT(*) FROM `tests` WHERE `test_user_id` = '" . $_COOKIE['uid'] . "';";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function chooseFavorite($id){
    $query = "SELECT * FROM `favorites` WHERE `favorite_user_id` = '" . $_COOKIE['uid'] . "' AND `favorite_test_id` = '" . $id . "'";
    return $this->returnActionQuery($query);
  }
  public function checkIfFavorite($id){
    $query = "SELECT * FROM `favorites` WHERE `favorite_user_id` = '" . $_COOKIE['uid'] . "' AND `favorite_test_id` = '" . $id . "'";
    $res = $this->returnActionQuery($query);
    if ($res && mysqli_num_rows($res) > 0) {
      return true;
    } else {
      return false;;
    }
  }
  public function actionFavorite($query){
    return $this->returnActionQuery($query);
  }
  public function addQues($ques, $test_id, $types_ques, $types_answ){
    $query = "INSERT INTO `questions` (`ques_text`, `ques_test_id`, `ques_type`, `ques_score`, `ques_ans_type`) VALUES ('$ques', '$test_id', '$types_ques', '1', '$types_answ');";
    return $this->returnLastId($query);
  }
  public function addAns($text, $ques_id, $correct){
    $query = "INSERT INTO `answers` (`answer_text`, `answer_ques_id`, `answer_correct`) VALUES ('$text', '$ques_id', '$correct');";
    $this->actionQuery($query);
  }
  public function addInfo($query){
    return $this->returnLastId($query);
  }
  public function checkIfExistName(){
    $query = "SELECT * FROM `tests`  WHERE `test_name` =  '" . $_POST['nameTxt'] . "' LIMIT 1";
    return $this->returnActionQuery($query);
  }
  public function getTest($id){
    $query = "SELECT `tests`.*, `method_name`, `user_name`, `subject_name`, `user_surname` FROM `tests`
     LEFT JOIN `methods` ON  `test_method_id` = `method_id`
     LEFT JOIN `users` ON  `test_user_id` = `user_id`
    LEFT JOIN `subjects` ON  `test_subject_id` = `subject_id`
     WHERE `test_id` = '" . $id . "'";
     return $this->returnAssoc($query);
  }

  public function solveAllTest($id){
    $query = "SELECT * FROM `test_status` WHERE `test_status_test_id` = '$id' AND `test_status_is_completed` = 0";
    $row =  $this->returnAllAssoc($query);
    foreach ($row as $item) {
      if ($item['test_status_doe'] - time() <= 0){
        $this->actionQuery("UPDATE `test_status` SET `test_status_is_completed` = 1 WHERE `test_status_id` = '" . $item['test_status_id'] . "';");
        $this->actionQuery("INSERT INTO `marks` (`mark_user_id`, `mark_value`, `mark_test_id`) VALUES ('" . $item['test_status_user_id'] . "', '2', '$id');");
      }
    }
  }

  public function getAllTestMarks($id){
    $query = "SELECT *, `test_method_id`, `test_id`, `user_name`, `user_surname` FROM `marks` LEFT JOIN `tests` ON `test_id` = `mark_test_id` LEFT JOIN `users` ON `user_id` = `mark_user_id` WHERE `mark_test_id` = '$id';";
    $masCheck = [];
    $data = array();
    $row = $this->returnAllAssoc($query);
    foreach ($row as $item) {
      if (in_array($item['mark_user_id'], $masCheck)) {
        continue;
      }
      if ($item['test_method_id'] == 1) {
        $query = "SELECT `user_name`, `user_surname`, `mark_value`, `user_id` FROM `marks` LEFT JOIN `users` ON `user_id` = `mark_user_id` WHERE `mark_user_id` = '" . $item['mark_user_id'] . "' AND `mark_test_id` = '" . $id . "' ORDER BY `mark_id` LIMIT 1;";
        array_push($data, $this->returnAssoc($query));
      } elseif ($item['test_method_id'] == 2) {
        $query = "SELECT `user_name`, `user_surname`, AVG(`mark_value`) AS `mark_value`, `user_id` FROM `marks` LEFT JOIN `users` ON `user_id` = `mark_user_id` WHERE `mark_user_id` = '" . $item['mark_user_id'] . "' AND `mark_test_id` = '" . $id . "' ORDER BY `mark_id`;";
        array_push($data, $this->returnAssoc($query));
      } elseif ($item['test_method_id'] == 3) {
        $query = "SELECT `user_name`, `user_surname`, `mark_value`, `user_id` FROM `marks` LEFT JOIN `users` ON `user_id` = `mark_user_id` WHERE `mark_user_id` = '" . $item['mark_user_id'] . "' AND `mark_test_id` = '" . $id . "' ORDER BY `mark_id` DESC LIMIT 1;";
        array_push($data, $this->returnAssoc($query));
      }
      array_push($masCheck, $item['mark_user_id']);
    }
    return $data;
  }

  public function getCountAttempts($id){
    $query = "SELECT COUNT(*) FROM `marks`  WHERE `mark_test_id` = '$id'";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function getMarkTotal($id){
    $query = "SELECT AVG(`mark_value`) as `avg` FROM `marks` WHERE `mark_test_id` = '$id'";
    $row = $this->returnAssoc($query);
    return $row['avg'];
  }

  public function getCountLikes($id){
    $query = "SELECT COUNT(*) FROM `likes` WHERE `like_test_id` = '$id'";
    $row = $this->returnAssoc($query);
    return $row['COUNT(*)'];
  }

  public function checkIfPutLike($id){
    $query = "SELECT COUNT(*) FROM `likes` WHERE `like_user_id` = '" . $_COOKIE['uid'] . "' AND `like_test_id` = '$id'";
    $row = $this->returnAssoc($query);
    if ($row['COUNT(*)'] > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function removeLike($id){
    $query = "DELETE FROM `likes` WHERE `like_user_id` = '" . $_COOKIE['uid'] . "' AND `like_test_id` = '$id'";
    $queryRemove = "UPDATE `tests` SET `test_likes` = `test_likes`-1 WHERE `test_id` = $id;";
    $this->actionQuery($queryRemove);
    return $this->actionQuery($query);
  }

  public function putLike($id){
    $query = "INSERT INTO `likes`(`like_user_id`, `like_test_id`) VALUES ('" . $_COOKIE['uid'] . "','$id')";
    $queryAdd = "UPDATE `tests` SET `test_likes` = `test_likes`+1 WHERE `test_id` = $id;";
    $this->actionQuery($queryAdd);
    return $this->actionQuery($query);
  }

  public function getMark($type, $id){
    if ($type == 1) {
      $query = "SELECT `mark_value` FROM `marks` WHERE `mark_user_id` = '" . $_COOKIE['uid'] . "' AND `mark_test_id` = '$id' LIMIT 1";
    } elseif ($type == 2) {
      $query = "SELECT AVG(`mark_value`) as `mark_value` FROM `marks` WHERE `mark_user_id` = '" . $_COOKIE['uid'] . "' AND `mark_test_id` = '$id'";
    } else{
      $query = "SELECT `mark_value` FROM `marks` WHERE `mark_user_id` = '" . $_COOKIE['uid'] . "' AND `mark_test_id` = '$id' ORDER BY `mark_id` DESC  LIMIT 1";
    }
    $row = $this->returnAssoc($query);
    if (empty($row['mark_value'])) {
      return 0;
    }
    return $row['mark_value'];
  }

  public function searchTest($val){
    $query = "SELECT `test_id`, `test_name` FROM `tests` WHERE LOWER(`test_name`) LIKE '%" . mb_strtolower($val) . "%' LIMIT 15;";
    return $this->returnAllAssoc($query);
  }

  public function getNumAtt($id){
    $query = "SELECT * FROM `test_status` WHERE `test_status_test_id` = '" . $id . "' AND `test_status_user_id` = '" . $_COOKIE['uid'] . "' AND `test_status_is_completed` = '1'";
    return $this->returnNumRows($query);
  }
  public function checkTimeAttempt($id){
    $query = "SELECT * FROM `test_status` WHERE `test_status_test_id` = '" . $_POST['id'] . "' AND `test_status_user_id` = '" . $_COOKIE['uid'] . "'";
    return $this->returnActionQuery($query);
  }
}
