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
          $text .= '<a href="group/' . $item['user_id'] . '"> <img src="./assets/img_group/' . $item['group_img'] . '" alt=""></a>';
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
