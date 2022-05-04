<?php
class GroupController extends Controller{
  private $groupModel;
  public function __construct(){
    parent::__construct();
    $this->groupModel = new Group();
  }
  public function actionGroups(){
    $title = "Классы";
    $styles = [CSS . '/groups.css'];
    $scripts = [JS . '/groups.js'];
    $totalGroup = $this->groupModel->getCount();
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/groups.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionAdd(){
    $title = "Создание Класса";
    if (isset($_POST['name'])) {
      $name = $this->helper->escape_srting($_POST['name']);
      $describe = $this->helper->escape_srting($_POST['describe']);
      if ($_FILES['img']['error'] == 0) {
        $filename = $this->groupModel->addImgGroup();
        move_uploaded_file($_FILES['img']['tmp_name'], './assets/img_group/' . $filename);
      }
      $allow = (isset($_POST['allow'])) ? 1 : 0;
      $code = $this->helper->generationToken(8);
      $doc = time();
      $last_id = $this->groupModel->addGroup($name, $describe, $filename, $allow, $code, $doc, $this->getUser()['user_organization_id']);
      header("Location: ./group/" . $last_id);
    }
    $styles = [CSS . '/group_add.css'];
    $scripts = [JS . '/group_add.js'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/group_add.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionAddrecord($data){
    $id_group = $data[0];
    if (isset($_POST['text'])) {
      $doc = time();
      $record_id = $this->groupModel->addRecord($_POST['text'], $_POST['type'], $id_group, $doc);
      $file = 'img';
      $cnt = count($_FILES['img']['name']);
      if ($_FILES['img']['error'][0] != 4) {
        for ($i=0; $i < $cnt; $i++) {
          $filename = $this->groupModel->addImg();
          move_uploaded_file($_FILES[$file]['tmp_name'][$i],  './assets/img_record/' . $filename);
          $this->groupModel->addRecordImg($record_id, $filename);
        }
      }
      header("Location: ../group/" . $id_group);
    }


    if (!$this->groupModel->checkIfUserParticipant($id_group)) {
      header('Location: ../report/noquery');
    }
    $group = $this->groupModel->getViewGroup($id_group);
    if ($group['group_allow_add'] == 0 && !$this->groupModel->checkIfUserAdmin($id_group)) {
      header('Location: ../report/noquery');
    }
    $title = "Добавление записи";
    $types = $this->groupModel->getAllTypes($id_group);
    $styles = [CSS . '/add_record.css'];
    $scripts = [JS . '/add_record.js'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/add_record.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionGroup($data){
    $id_group = $data[0];
    $group = $this->groupModel->getViewGroup($id_group);
    $total = $this->groupModel->getUsersCount($id_group);
    $totalRecords = $this->groupModel->countRecords($id_group);
    $participantBool = false;
    $adminBool = $this->groupModel->checkIfUserAdmin($id_group);
    $btnActionText = '';
    $res = $this->groupModel->getUserRequest($id_group);
    if (mysqli_num_rows($res) > 0) {
      $masGroup = mysqli_fetch_assoc($res);
      if ($masGroup['request_status_id'] == 1) {
        $btnActionText = '<button  class="action-friend" onclick="removeRequest(\'' . $masGroup['request_id'] . '\')">Отменить заявку</button>';
       }else {
          $btnActionText = '<button class="action-friend" onclick="deleteRequest(\'' . $masGroup['request_id'] . '\')">Выйти из группы</button>';
          $participantBool = true;
        }
      } else {
          $masFriend['request_id'] = -1;
          $btnActionText = '<button class="action-friend add" onclick="addRequest(\'' . $group['group_id'] . '\')">Отправить заявку</button>';
      }
    $split = explode("-", $group['group_doc']);
    $doc = $split[2] . "." . $split[1] . "." . $split[0];
    $title = $group['group_name'];
    $styles = [CSS . '/group_view.css'];
    $scripts = [JS . '/group_view.js'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/group_view.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionUpdaterecords(){
    $text = "";
    $id_group = $_POST['id_group'];
    $total = $this->groupModel->countRecords($id_group);
    $querySearch = "SELECT `record_id`, `user_name`, `user_surname`, `type_name`, `record_date`, `record_text`, `user_id`, `type_id` FROM `records`
                    LEFT JOIN `users` ON `user_id` = `record_user_id`
                    LEFT JOIN  `types` ON `type_id` = `record_type_id`
                    WHERE `record_group_id` = '$id_group'
                    ORDER BY `record_id` DESC;";
    $queryAll = "SELECT `record_id`, `user_name`, `user_surname`, `type_name`, `record_date`, `record_text` , `user_id`, `type_id` FROM `records`
                    LEFT JOIN `users` ON `user_id` = `record_user_id`
                    LEFT JOIN  `types` ON `type_id` = `record_type_id`
                    WHERE `record_group_id` = '$id_group'
                    ORDER BY `record_id` DESC LIMIT " . (int)$_POST['border'] . ", " . 20 . ";";
    $funFilter = '';
    $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<h1 class='no-records'>Здесь пока-что нет записей</h1>");
    foreach ($data as $item) {
      $imgs = $this->groupModel->getRecordImgs($item['record_id']);
      $comments = $this->groupModel->getComments($item['record_id']);
      $numLikes = $this->groupModel->getCountLikes($item['record_id']);
      $classLike = '';
      if ($this->groupModel->checkIfPutLike($item['record_id'])) {
        $classLike = 'active';
      }
      $split = explode("-", $item['record_date']);
      $doc = $split[2] . "." . $split[1] . "." . $split[0];
      $text .= '<div class="record_block" id="record' . $item['record_id'] . '">';
      $text .= '<span class="type" id="type' . $item['type_id'] . '">' .  $item['type_name'] . '</span>';
      $text .= '<div class="text">';
      $text .= '<p>' . $item['record_text'] . '</p>';
      $text .= '</div>';
      $text .= '<div class="img_block">';
      foreach ($imgs as $img) {
        $text .= '<img src="../assets/img_record/' . $img['record_img_path'] . '">';
      }
      $text .= '</div>';
      $text .= '<div class="record_footer">';
      $text .= '<div>';
      $text .= '<i class="fa fa-heart ' . $classLike . '" id="like_icon' . $item['record_id'] . '" onclick="putLike(\'' . $item['record_id'] . '\')"></i><span id="numLikes' . $item['record_id'] . '">' . $numLikes . '</span>';
      $text .= '</div>';
      $text .= '<div>';
      $text .= '<span><a href="../view/' . $item['user_id'] . '">' . $item['user_name'] . ' ' .$item['user_surname']  .  '</a></span><span>' . $doc . '</span>';
      $text .= '</div>';
      $text .= '</div>';
      $text .= '<div class="comments">';
      $text .= '<div class="body_comments" id="comment' . $item['record_id'] . '">';
      foreach ($comments as $comment) {
        $date = $comment['comment_date'];
        $text .= '<div>';
        if (is_null($comment['user_img']) || $comment['user_img'] == "") {
          $text .= '<a href="../view/' . $comment['user_id'] . '"><img src="../assets/img/profile.png" alt=""></a>';
        } else{
          $text .= '<a href="../view/' . $comment['user_id'] . '"> <img src="../assets/img_user/' . $comment['user_img'] . '" alt=""></a>';
        }
        $text .= '<div>';
        $text .= '<a href="../view/' . $comment['user_id'] . '">' . $comment['user_name'] . ' ' . $comment['user_surname'] .  '</a>';
        $text .= '<p>' . $comment['comment_text'] . '</p>';
        $text .= '<span>' . $date . '</span>';
        $text .= '</div>';
        $text .= '</div>';
      }
      $text .= '</div>';
      $text .= '<div class="form_comments">';
      $text .= '<input type="text" placeholder="Ответить..." id="input_comment' . $item['record_id']  . '"><button onclick="addComment(\'' . $item['record_id'] . '\', \'' . $this->getUser()['user_id'] . '\',\'' . $this->getUserModel()->getImgWithDefault() . '\',\'' . $this->getUser()['user_name'] . '\',\'' . $this->getUser()['user_surname'] . '\')" title="' . $item['record_id'] . '" class="btn_comment"><i class="fa fa-paper-plane"></i></button>';
      $text .= '</div>';
      $text .= '</div>';
      $text .= '</div>';
    }
      if ($text == ""){
        $text = "<div class='empty'><h4>Не найдено ни одной записи</h4><div>";
      }
      echo $text;
  }

  public function actionNews(){
    $title = 'Новости';
    $styles = [CSS . '/news.css'];
    $scripts = [JS . '/news.js'];
    $total = $this->groupModel->getCountAllRecords();
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/news.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionUpdatenews(){
    $text = "";
    $total = $this->groupModel->getCountAllRecords();
    $querySearch = "SELECT `record_id`, `user_name`, `user_surname`, `type_name`, `record_date`, `record_text`, `user_id`, `group_img`, `group_name`, `group_id`, `type_id` FROM `records`
                    LEFT JOIN `users` ON `user_id` = `record_user_id`
                    LEFT JOIN  `types` ON `type_id` = `record_type_id`
                    LEFT JOIN `groups` ON `record_group_id` = `group_id`
                    LEFT JOIN `requests` ON `request_group_id` = `group_id`
                    WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_status_id` = 2
                    ORDER BY `record_id` DESC;";
    $queryAll = "SELECT `record_id`, `user_name`, `user_surname`, `type_name`, `record_date`, `record_text`, `user_id`, `group_img`, `group_name`, `group_id`, `type_id` FROM `records`
                    LEFT JOIN `users` ON `user_id` = `record_user_id`
                    LEFT JOIN  `types` ON `type_id` = `record_type_id`
                    LEFT JOIN `groups` ON `record_group_id` = `group_id`
                    LEFT JOIN `requests` ON `request_group_id` = `group_id`
                    WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_status_id` = 2
                    ORDER BY `record_id` DESC LIMIT " . (int)$_POST['border'] . ", " . 5 . ";";
    $funFilter = '';
    $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<h1 class='no-records'>Здесь пока-что ничего нет, вам необходимо <a href='groups'>присоединиться к классу</a></h1>");
    foreach ($data as $item) {
      $imgs = $this->groupModel->getRecordImgs($item['record_id']);
      $comments = $this->groupModel->getComments($item['record_id']);
      $numLikes = $this->groupModel->getCountLikes($item['record_id']);
      $classLike = '';
      if ($this->groupModel->checkIfPutLike($item['record_id'])) {
        $classLike = 'active';
      }
      $split = explode("-", $item['record_date']);
      $doc = $split[2] . "." . $split[1] . "." . $split[0];
      $text .= '<div class="record_block" id="record' . $item['record_id'] . '">';
      $text .= '<div class="record_header">';
      if (is_null($item['group_img']) || $item['group_img'] == "") {
        $text .= '<a href="group/' . $item['group_id'] . '"><img src="./assets/img/group.png" alt=""></a>';
      } else{
        $text .= '<a href="group/' . $item['group_id'] . '"> <img src="./assets/img_group/' . $item['group_img'] . '" alt=""></a>';
      }
      $text .= '<a href="group/' . $item['group_id'] . '">' .  $item['group_name'] . '</a>';
      $text .= '</div>';
      $text .= '<span class="type" id="type' . $item['type_id'] . '">' .  $item['type_name'] . '</span>';
      $text .= '<div class="text">';
      $text .= '<p>' . $item['record_text'] . '</p>';
      $text .= '</div>';
      $text .= '<div class="img_block">';
      foreach ($imgs as $img) {
        $text .= '<img src="./assets/img_record/' . $img['record_img_path'] . '">';
      }
      $text .= '</div>';
      $text .= '<div class="record_footer">';
      $text .= '<div>';
      $text .= '<i class="fa fa-heart ' . $classLike . '" id="like_icon' . $item['record_id'] . '" onclick="putLike(\'' . $item['record_id'] . '\')"></i><span id="numLikes' . $item['record_id'] . '">' . $numLikes . '</span>';
      $text .= '</div>';
      $text .= '<div>';
      $text .= '<span><a href="./view/' . $item['user_id'] . '">' . $item['user_name'] . ' ' .$item['user_surname']  .  '</a></span><span>' . $doc . '</span>';
      $text .= '</div>';
      $text .= '</div>';
      $text .= '<div class="comments">';
      $text .= '<div class="body_comments" id="comment' . $item['record_id'] . '">';
      foreach ($comments as $comment) {
        $date = $comment['comment_date'];
        $text .= '<div>';
        if (is_null($comment['user_img']) || $comment['user_img'] == "") {
          $text .= '<a href="./view/' . $comment['user_id'] . '"><img src="./assets/img/profile.png" alt=""></a>';
        } else{
          $text .= '<a href="./view/' . $comment['user_id'] . '"> <img src="./assets/img_user/' . $comment['user_img'] . '" alt=""></a>';
        }
        $text .= '<div>';
        $text .= '<a href="./view/' . $comment['user_id'] . '">' . $comment['user_name'] . ' ' . $comment['user_surname'] .  '</a>';
        $text .= '<p>' . $comment['comment_text'] . '</p>';
        $text .= '<span>' . $date . '</span>';
        $text .= '</div>';
        $text .= '</div>';
      }
      $text .= '</div>';
      $text .= '<div class="form_comments">';
      $text .= '<input type="text" placeholder="Ответить..." id="input_comment' . $item['record_id']  . '"><button onclick="addComment(\'' . $item['record_id'] . '\', \'' . $this->getUser()['user_id'] . '\',\'' . $this->getUserModel()->getImgWithDefaultOther() . '\',\'' . $this->getUser()['user_name'] . '\',\'' . $this->getUser()['user_surname'] . '\')" title="' . $item['record_id'] . '" class="btn_comment"><i class="fa fa-paper-plane"></i></button>';
      $text .= '</div>';
      $text .= '</div>';
      $text .= '</div>';
    }
      if ($text == ""){
        $text = "<div class='empty'><h4>Не найдено ни одной записи</h4><div>";
      }
      echo $text;
  }

  public function actionAddcomment(){
    $time_dos = time();
    $text = $this->helper->escape_srting($_POST['text']);
    $res = $this->groupModel->addComment($_POST['id_record'], $text, $time_dos);
    if ($res) {
      echo "success";
    } else {
      echo "error";
    }
  }

  public function actionPutlike(){
    if ($this->groupModel->checkIfPutLike($_POST['id_record'])) {
      $this->groupModel->removeLike($_POST['id_record']);
      echo "remove";
    } else {
      $this->groupModel->putLike($_POST['id_record']);
      echo "put";
    }
  }

  public function actionGroupaction(){
    if (isset($_POST['id_group'])) {
      if ($_POST['type'] == 'deleteRequest' || $_POST['type'] == 'removeRequest') {
        if ($this->groupModel->checkIfIsAdmin($_POST['id_group'])) {
          echo "admin";
          exit;
        }
        $res = $this->groupModel->delete($_POST['id_group']);
        if ($res) {
          echo "success";
        } else{
          echo "error";
        }
      } elseif ($_POST['type'] == 'addRequest') {
        $res = $this->groupModel->add($_POST['id_group']);
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

  public function actionSearch(){
    if (is_null($this->getUser()['user_organization_id'])) {
      header("Location: ./report/no_organization");
    }
    $title = "Поиск классов";
    $styles = [CSS . '/group_search.css', CSS . '/tooltip.css'];
    $scripts = [JS . '/group_search.js'];
    $totalGroup = $this->groupModel->getAllCount();
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/group_search.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionUpdategroupssearch(){
    $text = "";
    $total =  $this->groupModel->getAllCount();
    $querySearch = "SELECT `group_name`, `group_img`, `organization_name`,  `group_id` FROM `groups`
                    LEFT JOIN `organizations` ON `organization_id` = `group_organization_id`
                    WHERE  `group_organization_id` = '" . $this->getUser()['user_organization_id'] . "' OR `group_organization_id` = NULL
                  ORDER BY `group_name` DESC;";
    $queryAll = "SELECT `group_name`, `group_img`, `organization_name`,  `group_id` FROM `groups`
                    LEFT JOIN `organizations` ON `organization_id` = `group_organization_id`
                    WHERE  `group_organization_id` = '" . $this->getUser()['user_organization_id'] . "' OR `group_organization_id` = NULL
                  ORDER BY `group_name` DESC LIMIT " . (int)$_POST['border'] . ", " . 20 . ";";
    $funFilter = 'Helper::searchFilterGroup';
    $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<div class='no-group'><h3>На сайте пока что нет классов у данной организации :( </h3><a href='settings'>Выбрать другую организацию</a></div>");
    $counter = 0;
    foreach ($data as $item) {
      if ($this->groupModel->checkIsMyGroup($item['group_id'])) {
        continue;
      }
     $text .= '<div id="group' . $item['group_id'] . '">';
     if (is_null($item['group_img']) || $item['group_img'] == "") {
       $text .= '<a href="group/' . $item['group_id'] . '"><img src="./assets/img/group.png" alt=""></a>';
     } else{
       $text .= '<a href="group/' . $item['group_id'] . '"> <img src="./assets/img_group/' . $item['group_img'] . '" alt=""></a>';
     }
     $text .= '<div>';
     $text .= '<div>';
     $text .= '<a href="./group/' . $item['group_id'] . '">' . $item['group_name'] . '</a>';
     $text .= '<a href="./group/' . $item['group_id'] . '">' . $item['organization_name'] . '</a>';
     $text .= '</div>';
     $text .= '<button type="button" aria-label="Отправить заявку" onclick="addRequest(\'' . $item['group_id'] . '\')" data-microtip-position="top-left" role="tooltip"><i class="fa fa-user-plus" aria-hidden="true"></i></button>';
     $text .= '</div>';
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

  public function actionRequestadding(){
    if (isset($_POST['id'])) {
      $res = $this->groupModel->add($_POST['id']);
      if ($res) {
        echo "success";
      } else{
        echo "error";
      }
    }
  }

  public function actionUpdategrouprequests(){
    $text = "";
    $total = $this->groupModel->countRequests();
    $querySearch = "SELECT `group_name`, `group_id`, `group_img`, `request_id`, `organization_name` FROM `groups`
                    LEFT JOIN `requests` ON `group_id` = `request_group_id`
                    LEFT JOIN `organizations` ON `organization_id` = `group_organization_id`
                    WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_status_id` = 1
                    ORDER BY `group_name` DESC;";
    $queryAll = "SELECT `group_name`, `group_id`, `group_img`, `request_id`, `organization_name`  FROM `groups`
                 LEFT JOIN `requests` ON `group_id` = `request_group_id`
                 LEFT JOIN `organizations` ON `organization_id` = `group_organization_id`
                 WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_status_id` = 1
                 ORDER BY `group_name` DESC LIMIT " . (int)$_POST['border'] . ", " . 50 . ";";
    $funFilter = 'Helper::searchFilterGroup';
    $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<div class='no-group'><h3>Вы пока-что не отправляли заявок :( </h3><a href='group-search'>Найти класс</a></div>");
    $counter = 0;

    foreach ($data as $item) {
      $text .= '<div id="group' . $item['request_id'] . '">';
      if (is_null($item['group_img']) || $item['group_img'] == "") {
        $text .= '<a href="group/' . $item['group_id'] . '"><img src="./assets/img/group.png" alt=""></a>';
      } else{
        $text .= '<a href="group/' . $item['group_id'] . '"> <img src="./assets/img_group/' . $item['group_img'] . '" alt=""></a>';
      }
      $text .= '<div>';
      $text .= '<a href="group/' . $item['group_id'] . '">' . $item['group_name'] . '</a>';
      $text .= '<span>' . $item['organization_name'] . '</span>';

      $text .= '<div><button onclick="deleteRequest(\'' . $item['request_id'] . '\')">Удалить заявку</button><a href="./group/' . $item['group_id'] . '">Смотреть описание</a></div>';
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

  public function actionRequestgroupdeleted(){
    if (isset($_POST['id'])) {
          $res = $this->groupModel->delete($_POST['id']);
          if ($res) {
            echo "success";
          } else{
            echo "error";
          }
    }
  }

  public function actionRequests(){
    $title = "Мои заявки";
    $styles = [CSS . '/requests.css'];
    $scripts = [JS . '/group_requests.js'];
    $totalRequests = $this->groupModel->countRequests();
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/group_requests.html';
    $this->helper->outputCommonFoot($scripts);
  }



  public function actionUpdategroups(){
    $text = "";
    $total =  $this->groupModel->getCount();
    $querySearch = "SELECT `group_name`, `group_img`, `organization_name`, `user_name`, `group_id`, `user_surname`, `user_id` FROM `groups`
                    LEFT JOIN `users` ON `group_user_id` = `user_id`
                    LEFT JOIN `organizations` ON `organization_id` = `group_organization_id`
                    LEFT JOIN `requests` ON `request_group_id` = `group_id`
                    WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_status_id` = 2
                  ORDER BY `group_name` DESC;";
    $queryAll = "SELECT `group_name`, `group_img`, `organization_name`, `user_name`, `group_id`,`user_surname`, `user_id` FROM `groups`
                    LEFT JOIN `users` ON `group_user_id` = `user_id`
                    LEFT JOIN `organizations` ON `organization_id` = `group_organization_id`
                    LEFT JOIN `requests` ON `request_group_id` = `group_id`
                    WHERE `request_user_id` = '" . $_COOKIE['uid'] . "' AND `request_status_id` = 2
                  ORDER BY `group_name` DESC LIMIT " . (int)$_POST['border'] . ", " . 20 . ";";
    $funFilter = 'Helper::searchFilterGroup';
    $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<div class='no-group'><h3>Вы пока-что не состоите ни в одном классе :( </h3><a href='group-search'>Найти класс</a></div>");
    $counter = 0;
    foreach ($data as $item) {
        $text .= '<div id="group' . $item['group_id'] . '">';
        if (is_null($item['group_img']) || $item['group_img'] == "") {
          $text .= '<a href="group/' . $item['group_id'] . '"><img src="./assets/img/group.png" alt=""></a>';
        } else{
          $text .= '<a href="group/' . $item['group_id'] . '"> <img src="./assets/img_group/' . $item['group_img'] . '" alt=""></a>';
        }
        $text .= '<div>';
        $text .= '<a href="group/' . $item['group_id'] . '">' . $item['group_name'] . '</a>';
        $text .= '<span>' . $item['organization_name'] . '</span>';
        $text .= '<a href="view/' . $item['user_id'] . '">' . $item['user_name'] . ' ' . $item['user_surname']  . '</a>';
        $text .= '</div>';
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
}
