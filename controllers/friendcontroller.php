<?php
  class FriendController extends Controller{
    private $friendModel;
    public function __construct(){
      parent::__construct();
      $this->friendModel = new Friend();
    }
    public function actionFriends(){
      $title = "Мои друзья";
      $styles = [CSS . '/friends.css', CSS . '/tooltip.css'];
      $scripts = [JS . '/friends.js'];
      $row = $this->friendModel->countFriends();
      $totalFriend =  $row['COUNT(*)'];
      require_once   './views/common/head.html';
      require_once   './views/common/header.html';
      require_once  './views/common/nav.php';
      require_once  './views/friends.html';
      $this->helper->outputCommonFoot($scripts);
    }

    public function actionUpdatefriends(){
      $text = "";
      $total =  $this->friendModel->getCount();
      $querySearch = "SELECT `user_img`, `user_name`, `user_surname`, `organization_name`, `friend_id`, `user_id` FROM `users`
                    LEFT JOIN `friends` ON `friend_recipient_id` = '" . $_COOKIE['uid'] . "' OR `friend_sender_id` = '" . $_COOKIE['uid'] . "'
                    LEFT JOIN  `organizations` ON `organization_id` = `user_organization_id`
                    WHERE `friend_status_id` = '2' AND `user_id` != '" . $_COOKIE['uid'] . "' AND (`user_id` = `friend_sender_id` OR `user_id` = `friend_recipient_id`)
                    ORDER BY `user_surname` DESC;";
      $queryAll = "SELECT `user_img`, `user_name`, `user_surname`, `organization_name`, `friend_id`, `user_id` FROM `users`
                    LEFT JOIN `friends` ON `friend_recipient_id` = '" . $_COOKIE['uid'] . "' OR `friend_sender_id` = '" . $_COOKIE['uid'] . "'
                    LEFT JOIN  `organizations` ON `organization_id` = `user_organization_id`
                    WHERE `friend_status_id` = '2' AND `user_id` != '" . $_COOKIE['uid'] . "' AND (`user_id` = `friend_sender_id` OR `user_id` = `friend_recipient_id`)
                    ORDER BY `user_surname` DESC LIMIT " . (int)$_POST['border'] . ", " . 20 . ";";
      $funFilter = 'Helper::searchFilterFriend';
      $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<div class='no-friend'><h3>У вас пока-что нет друзей :( </h3><a href='friend-search'>Найти друзей</a></div>");
      $counter = 0;
      foreach ($data as $item) {
          $text .= '<div id="friend' . $item['friend_id'] . '">';
          if (is_null($item['user_img']) || $item['user_img'] == "") {
            $text .= '<a href="view/' . $item['user_id'] . '"><img src="./assets/img/profile.png" alt=""></a>';
          } else{
            $text .= '<a href="view/' . $item['user_id'] . '"> <img src="./assets/img_user/' . $item['user_img'] . '" alt=""></a>';
          }
          $text .= '<div>';
          $text .= '<a href="view/' . $item['user_id'] . '">' . $item['user_surname'] . ' ' . $item['user_name'] . '</a>';
          $text .= '<span>' . $item['organization_name'] . '</span>';
          $text .= '<a href="chat/' . $item['user_id'] . '">Написать сообщение</a>';
          $text .= '</div>';
          $text .= '<button class="btn-delete" aria-label="Удалить из друзей" data-microtip-position="bottom-left" role="tooltip" onclick="deleteFriend(\'' . $item['friend_id'] . '\')"><i class="fa fa-times" aria-hidden="true"></i></button>';
          $text .= '</div>';
          $counter++;
          if (isset($_COOKIE['search']) && $counter>=50){
            break;
          }
        }
      if ($text == ""){
        $text = "<div class='empty'>Не найдено ни одной записи</div>";
      }
      echo $text;
    }

    public function actionSearch(){
        $total =  $this->friendModel->countAllUsers();
        $genderModel = new Gender();
        $genders = $genderModel->getAll();
        $roleModel = new Role();
        $roles = $roleModel->getAll();
        $title = "Поиск друзей";
        $scripts = ['./assets/js/friend_search.js'];
        $styles = ['./assets/css/friend-search.css', './assets/css/main/tooltip.css'];
        require_once   './views/common/head.html';
        require_once   './views/common/header.html';
        require_once  './views/common/nav.php';
        require_once  './views/friend_search.html';
        $this->helper->outputCommonFoot($scripts);
    }

    public function actionFrienddeleted(){
      if (isset($_POST['id'])) {
            $res = $this->friendModel->delete($_POST['id']);
            if ($res) {
              echo "success";
            } else{
              echo "error";
            }
          }
    }

    public function actionFriendaccept(){
      if (isset($_POST['id'])) {
        $res = $this->friendModel->acceptFriendId($_POST['id']);
        if ($res) {
          echo "success";
        } else{
          echo "error";
        }
    }
    }

    public function actionMyrequests(){
      $title = "Мои заявки в друзья";
      $styles = [CSS . '/requests.css', CSS . '/tooltip.css'];
      $scripts = [JS . '/my_requests.js'];
      $totalRequests = $this->friendModel->countRequests();
      $totalMyRequests = $this->friendModel->countMyRequests();
      require_once   './views/common/head.html';
      require_once   './views/common/header.html';
      require_once  './views/common/nav.php';
      require_once  './views/my_requests.html';
      $this->helper->outputCommonFoot($scripts);
    }

    public function actionRequests(){
      $title = "Заявки в друзья";
      $styles = [CSS . '/requests.css', CSS . '/tooltip.css'];
      $scripts = [JS . '/requests.js'];
      $totalRequests = $this->friendModel->countRequests();
      $totalMyRequests = $this->friendModel->countMyRequests();
      require_once   './views/common/head.html';
      require_once   './views/common/header.html';
      require_once  './views/common/nav.php';
      require_once  './views/requests.html';
      $this->helper->outputCommonFoot($scripts);
    }

    public function actionView($data){
      $id_view = $data[0];
      $res = $this->friendModel->countViewFriends($id_view);
      $view = $this->friendModel->getViewUser($id_view);
      if ($id_view == $this->getUser()['user_id']) {
        header("Location: ../my");
        exit;
      }
      $btnActionText = '';
      $boolFriend = false;
      if (mysqli_num_rows($res) > 0) {
        $masFriend = mysqli_fetch_assoc($res);
        if ($masFriend['friend_status_id'] == 1) {
          if ($masFriend['friend_sender_id'] != $view['user_id']) {
            $btnActionText = '<button  class="action-friend" onclick="removeRequest(\'' . $masFriend['friend_id'] . '\')">Отменить заявку</button>';
          } else {
            $btnActionText = '<button class="action-friend" onclick="accessRequest(\'' . $masFriend['friend_id']  . '\')">Принять заявку</button>';
          }
          }else {
            $btnActionText = '<button class="action-friend" onclick="deleteFriend(\'' . $masFriend['friend_id'] . '\')">Удалить из друзей</button>';
            $boolFriend = true;
          }
        } else {
            $masFriend['friend_id'] = -1;
            $btnActionText = '<button class="action-friend add" onclick="addFriend(\'' . $view['user_id'] . '\')">Добавить в друзья</button>';
        }
      $split = explode("-", $view['user_dob']);
      $dob = $split[2] . "." . $split[1] . "." . $split[0];
      $totalFriend = 0;
      $title = $view['user_name'] . " " . $view['user_surname'];
      $styles = [CSS . '/view.css'];
      $scripts = [JS . '/view.js'];
      require_once   './views/common/head.html';
      require_once   './views/common/header.html';
      require_once  './views/common/nav.php';
      require_once  './views/view.html';
      $this->helper->outputCommonFoot($scripts);
    }

    public function actionFriendadding(){
      if (isset($_POST['id'])) {
        $res = $this->friendModel->add($_POST['id']);
        if ($res) {
          echo "success";
        } else{
          echo "error";
        }
      }
    }

    public function actionFriendaction(){
      if (isset($_POST['id_friend'])) {
        if ($_POST['type'] == 'deleteFriend' || $_POST['type'] == 'removeRequest') {
          $res = $this->friendModel->delete($_POST['id_friend']);
          if ($res) {
            echo "success";
          } else{
            echo "error";
          }
        } elseif ($_POST['type'] == 'addFriend') {
          $res = $this->friendModel->add($_POST['id_friend']);
          if ($res) {
            echo "success";
          } else{
            echo "error";
          }
        } elseif ($_POST['type'] == 'accessRequest') {
          $res = $this->friendModel->accept($_POST['id_friend']);
          if ($res) {
            echo "success";
          } else{
            echo "error";
          }
        } else {
          echo "error";
        }
      }
    }

    public function actionUpdaterequests(){
      $text = "";
      $total = $this->friendModel->countRequests();
      $querySearch = "SELECT `user_img`, `user_name`, `user_surname`, `organization_name`, `friend_id`, `user_id` FROM `users`
                    LEFT JOIN `friends` ON `friend_recipient_id` = '" . $_COOKIE['uid'] . "'
                    LEFT JOIN  `organizations` ON `organization_id` = `user_organization_id`
                    WHERE `friend_status_id` = '1' AND `user_id` = `friend_sender_id`
                    ORDER BY `user_surname` DESC;";
      $queryAll = "SELECT `user_img`, `user_name`, `user_surname`, `organization_name`, `friend_id`, `user_id` FROM `users`
                    LEFT JOIN `friends` ON `friend_recipient_id` = '" . $_COOKIE['uid'] . "'
                    LEFT JOIN  `organizations` ON `organization_id` = `user_organization_id`
                    WHERE `friend_status_id` = '1' AND `user_id` = `friend_sender_id`
                    ORDER BY `user_surname` DESC LIMIT " . (int)$_POST['border'] . ", " . 50 . ";";
      $funFilter = 'Helper::searchFilterRequest';
      $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<div class='no-friend'><h3>У вас пока-что нет заявок :( </h3><a href='friend-search'>Найти друзей</a></div>");
      $counter = 0;

        foreach ($data as $item) {
          $text .= '<div id="friend' . $item['friend_id'] . '">';
          if (is_null($item['user_img']) || $item['user_img'] == "") {
            $text .= '<a href="view/' . $item['user_id'] . '"><img src="./assets/img/profile.png" alt=""></a>';
          } else{
                $text .= '<a href="view/' . $item['user_id'] . '"> <img src="./assets/img_user/' . $item['user_img'] . '" alt=""></a>';
          }
          $text .= '<div>';
          $text .= '<a href="view/' . $item['user_id'] . '">' . $item['user_surname'] . ' ' . $item['user_name'] . '</a>';
          $text .= '<span>' . $item['organization_name'] . '</span>';

          $text .= '<div><button onclick="acceptRequest(\'' . $item['friend_id'] . '\')">Принять заявку</button><button class="del" onclick="removeRequest(\'' . $item['friend_id'] . '\')">Отклонить заявку</button><a href="view/' . $item['user_id'] . '">Смотреть профиль</a></div>';
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

    public function actionUpdatemyrequests(){
      $text = "";
      $total = $this->friendModel->countMyRequests();
      $querySearch = "SELECT `user_img`, `user_name`, `user_surname`, `organization_name`, `friend_id`, `user_id` FROM `users`
              LEFT JOIN `friends` ON `friend_sender_id` = '" . $_COOKIE['uid'] . "'
              LEFT JOIN  `organizations` ON `organization_id` = `user_organization_id`
              WHERE `friend_status_id` = '1' AND `user_id` = `friend_recipient_id`
              ORDER BY `user_surname` DESC;";
      $queryAll = "SELECT `user_img`, `user_name`, `user_surname`, `organization_name`, `friend_id`, `user_id` FROM `users`
              LEFT JOIN `friends` ON `friend_sender_id` = '" . $_COOKIE['uid'] . "'
              LEFT JOIN  `organizations` ON `organization_id` = `user_organization_id`
              WHERE `friend_status_id` = '1' AND `user_id` = `friend_recipient_id`
              ORDER BY `user_surname` DESC LIMIT " . (int)$_POST['border'] . ", " . 50 . ";";
      $funFilter = 'Helper::searchFilterMyRequest';
      $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<div class='no-friend'><h3>Вы пока-что не отправляли заявок :( </h3><a href='friend-search'>Найти друзей</a></div>");
      $counter = 0;

      foreach ($data as $item) {
        $text .= '<div id="friend' . $item['friend_id'] . '">';
        if (is_null($item['user_img']) || $item['user_img'] == "") {
          $text .= '<a href="view/' . $item['user_id'] . '"><img src="./assets/img/profile.png" alt=""></a>';
        } else{
              $text .= '<a href="view/' . $item['user_id'] . '"> <img src="./assets/img_user/' . $item['user_img'] . '" alt=""></a>';
        }
        $text .= '<div>';
        $text .= '<a href="view/' . $item['user_id'] . '">' . $item['user_surname'] . ' ' . $item['user_name'] . '</a>';
        $text .= '<span>' . $item['organization_name'] . '</span>';

        $text .= '<div><button onclick="deleteRequest(\'' . $item['friend_id'] . '\')">Удалить заявку</button><a href="./view/' . $item['user_id'] . '">Смотреть профиль</a></div>';
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

    public function actionUpdatesearchfriend(){
      $text = "";
      $total = $this->friendModel->countAllUsers();

       $check = false;

       $queryAll = "SELECT `user_id`, `user_img`, `user_name`, `user_surname`, `organization_name`, `user_dob` FROM `users`
                 LEFT JOIN  `organizations` ON `organization_id` = `user_organization_id`
                 WHERE  `user_id` != '" . $_COOKIE['uid'] . "'";

       if ($_POST['gender'] != $_POST['max_gender']){
         $queryAll .= " AND `user_gender_id` = '" . $_POST['gender'] . "'";
         $check = true;
       }

       if ($_POST['organization'] == "true" && $_POST['my_organization'] != '') {
         $queryAll .= " AND `user_organization_id` = '" . $_POST['my_organization'] . "'";
         $check = true;
       }

       if ($_POST['role'] != $_POST['max_role']){
         $queryAll .= " AND `user_role_id` = '" . $_POST['role'] . "'";
         $check = true;
       }

       if ($_POST['vk'] == "true"){
         $queryAll .= " AND `user_vk_id` != '0'";
         $check = true;
       }


       if ($_POST['sort'] != 'sort'){
         $queryAll .= " ORDER BY `" . $_POST['sort'] . "`";
         if ($_POST['sort'] == 'user_id'){
           $queryAll .= " DESC";
         }
       }




       $querySearch = $queryAll;

       if (!isset($_COOKIE['search'])){
         $queryAll .= "  LIMIT " . (int)$_POST['border'] . ", " . 21 . ";";
       }
        $funFilter = 'Helper::searchFilterFriendSearch';
        $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<div class='no-friend'><h3>Видимо все пользователи, которые есть на сайте у вас в друзьях :) </h3><a href='im'>Сообщения</a></div>");

         $counter = 0;



          foreach ($data as $item) {
            if ($this->friendModel->checkIfUserFriend($item['user_id']) > 0){
              continue;
            }
           $text .= '<div id="user' . $item['user_id'] . '">';
           if (is_null($item['user_img']) || $item['user_img'] == "") {
             $text .= '<a href="./view/' . $item['user_id'] . '"><img src="./assets/img/profile.png" alt=""></a>';
           } else{
             $text .= '<a href="./view/' . $item['user_id'] . '"><img src="./assets/img_user/' . $item['user_img'] . '" alt=""></a>';
           }
           $text .= '<div>';
           $text .= '<div>';
           $text .= '<a href="./view/' . $item['user_id'] . '">' . $item['user_surname'] . ' ' . $item['user_name'] . '</a>';
           $text .= '<a href="./view/' . $item['user_id'] . '">' . $item['organization_name'] . '</a>';
           $text .= '</div>';
           $text .= '<button type="button" aria-label="Отправить заявку" onclick="addFriend(\'' . $item['user_id'] . '\')" data-microtip-position="top-left" role="tooltip"><i class="fa fa-user-plus" aria-hidden="true"></i></button>';
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
