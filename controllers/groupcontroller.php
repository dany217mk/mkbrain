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

  public function actionGroup($data){
    $id_group = $data[0];
    $group = $this->groupModel->getViewGroup($id_group);
    $total = $this->groupModel->getUsersCount($id_group);
    $participantBool = $this->groupModel->checkIsMyGroup($id_group);
    $adminBool = $this->groupModel->checkIfUserAdmin($id_group);
    $title = $group['group_name'];
    $styles = [CSS . '/group_view.css'];
    $scripts = [JS . '/group_view.js'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/group_view.html';
    $this->helper->outputCommonFoot($scripts);
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
                    WHERE `request_user_id` = '" . $_COOKIE['uid'] . "'
                  ORDER BY `group_name` DESC;";
    $queryAll = "SELECT `group_name`, `group_img`, `organization_name`, `user_name`, `group_id`,`user_surname`, `user_id` FROM `groups`
                    LEFT JOIN `users` ON `group_user_id` = `user_id`
                    LEFT JOIN `organizations` ON `organization_id` = `group_organization_id`
                    LEFT JOIN `requests` ON `request_group_id` = `group_id`
                    WHERE `request_user_id` = '" . $_COOKIE['uid'] . "'
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
