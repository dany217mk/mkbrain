<?php
class TestController extends Controller{
  private $testModel;
  public function __construct(){
    parent::__construct();
    $this->testModel = new Test();
  }
  public function actionTests(){
    $title = "Тесты";
    $styles = [CSS . '/tests.css'];
    $scripts = [JS . '/tests.js'];
    $total = $this->testModel->getCount();
    $subjectModel = new Subject();
    $subjects = $subjectModel->getAll();
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/tests.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionTest($data){
    $id_view = $data[0];
    $view = $this->testModel->getTest($id_view);
    $title = $view['test_name'];
    $styles = [CSS . '/test.css'];
    $scripts = [];
    $con = DB::getConnection();
    $boolAtmpt = false;
    $queryCheck = "SELECT * FROM `test_status` WHERE `test_status_test_id` = '" . $id_view . "' AND `test_status_user_id` = '" . $_COOKIE['uid'] . "' AND `test_status_is_completed` = 0";
    $resCheck = mysqli_query($con, $queryCheck);
    if ($resCheck && mysqli_num_rows($resCheck) > 0){
      $attempt = mysqli_fetch_assoc($resCheck);
      if ($attempt['test_status_doe'] - time() <= 0){
        if ($view['test_toe'] != 0) {
          $queryUpd = "UPDATE `test_status` SET `test_status_is_completed` = 1 WHERE `test_status_test_id` = '" . $id_view . "' AND `test_status_user_id` = '" . $_COOKIE['uid'] . "';";
          mysqli_query($con, $queryUpd);
          $queryMark = "INSERT INTO `marks` (`mark_user_id`, `mark_value`, `mark_test_id`) VALUES ('" . $_COOKIE['uid'] . "', '2', '" . $id_view  . "');";
          mysqli_query($con, $queryMark);
          header("Location: ../report/numtryx");
          }
        }
        $boolAtmpt = true;
      } else {
        echo mysqli_num_rows($resCheck);
        $time_end = time()+$view['test_toe'];
        $query = "INSERT INTO `test_status` (`test_status_user_id`, `test_status_test_id`, `test_status_doe`) VALUES ('" . $_COOKIE['uid'] . "', '" . $id_view  . "', '$time_end');";
        $resInsStatus = mysqli_query($con, $query);
      }
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/test.php';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionTimechecking(){
    $resCheck = $this->testModel->checkTimeAttempt($_POST['id']);
    if ($resCheck && mysqli_num_rows($resCheck) > 0){
      $attempt = mysqli_fetch_assoc($resCheck);
      if ($attempt['test_status_doe'] - time() <= 0){
        echo "time_end";
      } else {
        echo $attempt['test_status_doe'] - time();
      }
    }
  }


  public function actionChecktest($data){
    $id_view = $data[0];
    $title = 'Итоги теста';
    $con = DB::getConnection();
    $styles = [CSS . '/test.css'];
    $scripts = [];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/check_test.php';
    $this->helper->outputCommonFoot($scripts);
  }



  public function actionTestview($data){
    $id_view = $data[0];
     $view = $this->testModel->getTest($id_view);
      $timeNum = intval($view['test_toe']);
      $timeProm = $timeNum;
      $timeX = 0;
      while ($timeProm > 3600){
         $timeX += 3600;
         $timeProm -= 3600;
      }
      $hour = $timeX/3600;
      $min = intval(($timeNum - $hour * 3600) / 60);
      $sec = $timeNum-$hour*3600-$min*60;

      if ($min == 60){
          $hour += 1;
          $min = 0;
      }
      $hour = $hour % 24;

      $minStr = $min;
      $secStr = $sec;
      $hourStr = $hour;

      if ($min < 10){
          $minStr = "0" . $minStr;
      }
      if ($sec < 10){
          $secStr = "0" . $secStr;
      }
      if ($hour < 10) {
         $hourStr = "0" . $hourStr;
      }

      $test_time = $hourStr . ':' . $minStr . ':' . $secStr;

   $numAtt = $this->testModel->getNumAtt($id_view);

   $mark = round($this->testModel->getMark($view['test_method_id'], $id_view), 2);

   $markText = $mark;

   if ($mark == 0) {
     $markText = 'ø';
   } else {
     $markText = $mark;
   }

    $title = $view['test_name'];
    $styles = [CSS . '/testview.css'];
    $scripts = [JS . '/testview.js'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/testview.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionConstructor(){
    $title = "Конструктор";
    $styles = [CSS . '/constructor.css'];
    $scripts = [JS . '/constructor.js'];
    $subjectModel = new Subject();
    $subjects = $subjectModel->getAll();
    $methodModel = new Method();
    $methods = $methodModel->getAll();
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/constructor.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionSendques(){
    if (isset($_POST['ques'])) {
        $ques = $this->helper->escape_srting(htmlentities($_POST['ques']));
        $types_answ = $this->helper->escape_srting(htmlentities($_POST['types_answ']));
        $types_ques = $this->helper->escape_srting(htmlentities($_POST['types_ques']));
        $answ = $_POST['answ'];
        $right_answ = $_POST['right_answ'];
        $test_id = $_POST['test_id'];
        if ($test_id == 0) {
          if (isset($_COOKIE['test_id'])) {
            $test_id = $_COOKIE['test_id'];
          }
        }
        $ques_id = $this->testModel->addQues($ques, $test_id, $types_ques, $types_answ);
        for ($i=0; $i < count($answ); $i++) {
            $ansMas = explode(',', $answ[$i]);
            $rgtMas = explode(',', $right_answ[$i]);
            for ($j=0; $j < count($ansMas); $j++) {
              $this->testModel->addAns($ansMas[$j], $ques_id, $rgtMas[$j]);
            }
        }
    }
  }

  public function actionInfoinserting(){
    if (isset($_POST['name'])) {
      $name = $this->helper->escape_srting(htmlentities($_POST['name']));
      $describe = $this->helper->escape_srting(htmlentities($_POST['describe']));
      $method = $this->helper->escape_srting(htmlentities($_POST['method']));
      $subject = $this->helper->escape_srting(htmlentities($_POST['subject']));
        $cnt = $_POST['cnt'];
        if (isset($_POST['attempts'])) {
          $attempts = $this->helper->escape_srting(htmlentities($_POST['attempts']));
        } else {
          $attempts = 0;
        }
        $show = $_POST['show'];
        $privacy = $_POST['privacy'];
        if (isset($_POST['time'])) {
              $time = $_POST['time'];
        } else {
              $time = 0;
        }
        $code = $this->helper->generationToken(8);
        $query = "INSERT INTO `tests` (`test_name`, `test_subject_id`, `test_describe`, `test_attempts`, `test_show_ans`, `test_code`, `test_privacy`, `test_method_id`, `test_user_id`, `test_toe`, `test_cnt`) VALUES ('$name', '$subject', '$describe', '$attempts', '$show', '$code', '$privacy', '$method', '" . $_COOKIE['uid'] . "', '$time', '$cnt');";
        $id = $this->testModel->addInfo($query);
        if ($id > 0) {
          setcookie('test_id', $id, time() + 600, '/');
          echo $id;
        } else {
          echo "error";
        }
    }
  }

  public function actionFormchecking(){
    if (isset($_POST['nameTxt'])){
        $res = $this->testModel->checkIfExistName();
        if ($res && mysqli_num_rows($res) == 0) {
          echo "";
        } else {
          echo "Тест с таким названием уже существует";
        }
      }
  }


  public function actionUpdatefavorite(){
    $del = $this->testModel->checkIfFavorite($_POST['id']);
    if ($del) {
      $query = "DELETE FROM `favorites` WHERE `favorite_test_id` = '" . $_POST['id'] . "' AND `favorite_user_id` = '" . $_COOKIE['uid'] . "'";
    } else {
      $query = "INSERT INTO `favorites` (`favorite_user_id`, `favorite_test_id`) VALUES ('" . $_COOKIE['uid'] . "', '" . $_POST['id'] . "');";
    }
    $res = $this->testModel->actionFavorite($query);
    if ($res) {
    if ($del) {
      echo "success-del";
    } else {
      echo "success-ins";
    }
    } else {
      echo "error";
    }
  }

  public function actionUpdatetests(){
    $text = "";
    $total = $this->testModel->getCount();
    $check = false;

  $queryAll = "SELECT `test_id`, `test_name`, `test_likes`, `test_attempts`, `subject_name`, `test_privacy`, `user_name`, `user_surname`, `user_id`, `test_img`
   FROM `tests`
   LEFT JOIN `subjects` ON `test_subject_id` = `subject_id`
   LEFT JOIN `users` ON `test_user_id` = `user_id`";

   if (isset($_POST['subject']) && $_POST['subject'] != "all") {
     $queryAll .= " WHERE `subject_id` = '" . $_POST['subject'] . "'";
     $check = true;
   }

   if (isset($_POST['privacy'])) {
     if (!$check) {
        $queryAll .= " WHERE `test_code` = '" . $_POST['privacy'] . "'";
     } else {
       $queryAll .= " AND `test_code` = '" . $_POST['privacy'] . "'";
     }
   }

   if (isset($_POST['popular']) && $_POST['popular'] != 'false') {
     $queryAll .= " ORDER BY `test_likes` DESC";
   } else {
     if (!isset($_POST['privacy'])) {
            $queryAll .= " ORDER BY `test_id` DESC";
     }
   }

   $querySearch = $queryAll;

   if (!isset($_COOKIE['search'])){
     $queryAll .= "  LIMIT " . (int)$_POST['border'] . ", " . 20 . ";";
   }

   $funFilter = 'Helper::searchFilterTest';
   $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<div class='no-test'><h3>На сайте пока нет открытых тестов :( Стань первым: </h3><a href='constructor'>Создать тест</a></div>");

    $counter = 0;

	 foreach ($data as $item) {
     if ($item['test_privacy'] == 1 && !isset($_POST['privacy'])) {
      continue;
    }
    $text .= '<div>';
    $text .= '<a class="block-link" href="testview/' . $item['test_id'] . '">';
    $text .= '<div>';
    $text .= '<div class="block">';
    $text .= '<div><h4>' . $item['test_name']  . '</h4><span>' . $item['subject_name'] . '</span></div>';
    if (is_null($item['test_img']) || $item['test_img'] == "") {
      $text .= '<img src="./assets/img/testImg.png">';
    } else{
    $text .= '<img src="./assets/img_test/' . $item['test_img'] . '">';
    }
    $text .= '</div>';
    $text .= '<div class="sett">';
      $text .= '<span><i class="fa fa-heart" aria-hidden="true"></i><b>' . $item['test_likes'] . '</b></span>';
    $text .= '</div>';
    $text .= '</div>';
    $text .= '</a>';
    $text .= '<div class="block-link-author">';
    $resFav = $this->testModel->chooseFavorite($item['test_id']);
    $fav_active = '';
    if ($resFav && mysqli_num_rows($resFav) > 0) {
      $text .= '<div><a href="view/' . $item['user_id']  .  '">' . $item['user_name'] . ' ' .  $item['user_surname'] . '</a></div><div><i class="fas fa-star" id="star' . $item['test_id'] . '" onclick="createFavorite(\'' . $item['test_id'] . '\')" aria-hidden="true"></i></div>';
    } else {
      $text .= '<div><a href="view/' . $item['user_id']  .  '">' . $item['user_name'] . ' ' .  $item['user_surname'] . '</a></div><div><i class="far fa-star" id="star' . $item['test_id'] . '" onclick="createFavorite(\'' . $item['test_id'] . '\')" aria-hidden="true"></i></div>';
    }
    $text .= '</div>';
    $text .= '</div>';

    $counter++;
    if (isset($_COOKIE['search']) && $counter>=50){
      break;
    }
	}
  if ($text == ""){
    $text = "<div class='empty'><h4>Не найдено ни одной записи</h4><div>";
  }
  echo $text;
  }
}
