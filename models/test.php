<?php
class Test extends Model{
  public function getCount(){
    $query = "SELECT COUNT(*) FROM `tests`";
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
  public function getNumAtt($id){
    $query = "SELECT * FROM `test_status` WHERE `test_status_test_id` = '" . $id . "' AND `test_status_user_id` = '" . $_COOKIE['uid'] . "' AND `test_status_is_completed` = '1'";
    return $this->returnNumRows($query);
  }
  public function checkTimeAttempt($id){
    $query = "SELECT * FROM `test_status` WHERE `test_status_test_id` = '" . $_POST['id'] . "' AND `test_status_user_id` = '" . $_COOKIE['uid'] . "'";
    return $this->returnActionQuery($query);
  }
}
