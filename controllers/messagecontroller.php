<?php
class MessageController extends Controller{
  private $messageModel;
  public function __construct(){
    parent::__construct();
    $this->messageModel = new Message();
  }
  public function actionIm(){
    $title = "Сообщения";
    $styles = [CSS . '/im.css'];
    $scripts = [JS . '/im.js'];
    $friendModel = new Friend();
    $total = $friendModel->getCount();
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/im.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionChat($data){
    $id_chat = $data[0];
    $title = "Сообщения";
    $styles = [CSS . '/chat.css'];
    $scripts = [JS . '/chat.js'];
    $friendModel = new Friend();
    $user_chat = $friendModel->getViewUser($id_chat);
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/chat.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionAddmsg(){
    if (isset($_POST['id'])) {
      $msg = $this->helper->escape_srting($_POST['msg']);
      $msg = htmlentities($msg);
      if (trim($msg) != "") {
        $time_dos = time();
        $res = $this->messageModel->add($_POST['id'], $time_dos, $msg);
        if ($res) {
          echo "success";
        }
      } else {
        echo "msg is empty!";
      }
    } else {
      echo 'error';
    }
  }

  public function actionGetchat(){
    if (isset($_POST['id'])) {
    $output = "";
     $res = $this->messageModel->getAllMsgs($_POST['id']);
     $dom = '';
     if(mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
              $splitDT = explode(" ", $row['msg_dos']);
              $splitT = explode(":", $splitDT[1]);
              $splitD = explode("-", $splitDT[0]);
              if ($dom != ($splitD[2] . "." . $splitD[1] . "." . $splitD[0])){
                $dom = $splitD[2] . "." . $splitD[1] . "." . $splitD[0];
                $output .= '<div class="dom">' . $dom . '</div>';
              }
                if($row['msg_sender_id'] == $_COOKIE['uid']){
                    $output .= '<div><div class="chat outgoing"><div class="details"><p>'. $row['msg'] .'</p></div></div><div class="dos">' . $splitT[0] . ':' . $splitT[1] . '</div></div>';
                }else{
                    $output .= '<div><div class="chat incoming">';
                    if (is_null($row['user_img']) || $row['user_img'] == "") {
                      $output .= '<img src="../assets/img/profile.png" alt="">';
                    } else{
                      $output .= '<img src="../assets/img_user/' . $row['user_img'] . '" alt="">';
                    }
                    $output .= '<div class="details"><p>'. $row['msg'] .'</p></div></div><div class="dos otg">' . $splitT[0] . ':' . $splitT[1] . '</div></div>';
                }
            }
        }else{
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        echo $output;
      } else {
        echo "error";
      }
  }

  public function actionReadmsg(){
    if (isset($_POST['id'])) {
        $this->messageModel->readAllMsgs($_POST['id']);
    }
  }

  public function actionUpdateim(){
    $friendModel = new Friend();
    $total = $friendModel->getCount();
	   $text = "";
    $querySearch = "SELECT `user_img`, `user_name`, `user_surname`, `user_id` FROM `users`
             LEFT JOIN `friends` ON `friend_recipient_id` = '" . $_COOKIE['uid'] . "' OR `friend_sender_id` = '" . $_COOKIE['uid'] . "'
             WHERE `friend_status_id` = '2' AND `user_id` != '" . $_COOKIE['uid'] . "' AND (`user_id` = `friend_sender_id` OR `user_id` = `friend_recipient_id`)
             ORDER BY `user_surname` DESC;";
    $queryAll = "SELECT `user_img`, `user_name`, `user_surname`, `user_id` FROM `users`
             LEFT JOIN `friends` ON `friend_recipient_id` = '" . $_COOKIE['uid'] . "' OR `friend_sender_id` = '" . $_COOKIE['uid'] . "'
             WHERE `friend_status_id` = '2' AND `user_id` != '" . $_COOKIE['uid'] . "' AND (`user_id` = `friend_sender_id` OR `user_id` = `friend_recipient_id`)
             ORDER BY `user_surname` DESC;";

      #"<div class='no-friend'><h3>У вас пока-что нет друзей :( </h3><a href='friend-search'>Найти друзей</a></div>"
      $funFilter = 'Helper::searchFilterIm';
      $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<div class='no-friend'><h3>У вас пока-что нет друзей :( </h3><a href='friend-search'>Найти друзей</a></div>");

    $counter = 0;

    if (!isset($_COOKIE['search'])) {
      for ($i=0; $i < count($data); $i++) {
       $resMsg = $this->messageModel->getLastMsg($data, $i);
       if (mysqli_num_rows($resMsg) > 0) {
         $msg = mysqli_fetch_assoc($resMsg);
         $data[$i]['msg_id'] = $msg['msg_id'];
        $data[$i]['msg_status'] = $msg['msg_status'];
         $data[$i]['msg_sender_id'] = $msg['msg_sender_id'];
         $data[$i]['msg'] = $msg['msg'];
       } else {
         $data[$i]['msg_id'] = 1000000000+$i;
         $data[$i]['msg_status'] = 1000000000+$i;
         $data[$i]['msg_sender_id'] = -1;
         $data[$i]['msg'] = '';
       }

      }
          usort($data, 'Helper::mySortMsgId');
    }

	 foreach ($data as $item) {
     if (!isset($_COOKIE['search']) && $item['msg_sender_id'] == -1) {
       continue;
     }

    $statusMsg = "hide";


    $text .= '<a href="chat/'. $item['user_id'] .'">';
    $text .= '<div class="content">';
    if (is_null($item['user_img']) || $item['user_img'] == "") {
      $text .= '<img src="' . IMG . '/profile.png" alt="">';
    } else{
      $text .= '<img src="' . IMG_USER . '/' . $item['user_img'] . '" alt="">';
    }
    $text .= '<div class="details">';
    $text .= '<span>'. $item['user_name']. " " . $item['user_surname'] .'</span>';
    if (!isset($_COOKIE['search']) && $item['msg_sender_id'] != -1){
        $msg = $item['msg'];
        if (mb_strlen($msg) > 50) {
          $msg = mb_substr($msg,0, 50) . "...";
        }
        $you = "";
        if ($item['msg_sender_id'] == $_COOKIE['uid']){
          $you = "You: ";
        }
        $text .= '<p>'. $you . $msg .'</p>';
        if ($item['msg_status'] == 1 && $item['msg_sender_id'] != $_COOKIE['uid']) {
            $statusMsg = "";
        }
        if ($item['msg_sender_id'] == $_COOKIE['uid'] && $item['msg_status'] == 1) {
          $statusMsg = "hide-gray";
        }
    }
    $text .= '</div>';
    $text .= '</div>';
    $text .= '<div class="status-dot '. $statusMsg .'"><i class="fa fa-circle"></i></div>';
    $text .= '</a>';


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
