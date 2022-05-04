<?php
class ServiceController extends Controller{
  public function actionService(){
    $title = 'Сервисы';
    $styles = [CSS . '/services.css'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/services.html';
    $this->helper->outputCommonFoot();
  }
  public function actionTeacher(){
    if (is_null($this->getUser()['user_organization_id'])) {
      header("Location: ./report/no_organization");
    }
    $title = 'Панель учителя';
    $styles = [CSS . '/services.css'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/teacher.html';
    $this->helper->outputCommonFoot();
  }
  public function actionDirector(){
    $orgBool = false;
    if (!is_null($this->getUser()['user_organization_id'])) {
      $orgBool = true;
    }
    $title = 'Панель учителя';
    $styles = [CSS . '/services.css'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/director.html';
    $this->helper->outputCommonFoot();
  }

  public function actionAddorganization(){
    $organizationModel = new Organization();
    if (isset($_POST['name'])) {
      $name =  $this->helper->escape_srting($_POST['name']);
      $organizationModel->add($name);
      header("Location: ./report/success");
    }
    $title = 'Создание организации';
    $styles = [CSS . '/add_org.css'];
    $scripts = [JS . '/add_org.js'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/add_org.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionOrgGroups(){
    $groupModel = new Group();
    $title = 'Классы ' . $this->getUser()['organization_name'];
    $total = $groupModel->getCountMyOrgGroup($this->getUser()['user_organization_id']);
    $styles = [CSS . '/list_services.css'];
    $scripts = [JS . '/org_groups.js'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/org_groups.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionOrgUsers(){
    $title = 'Пользователи ' . $this->getUser()['organization_name'];
    $total = $this->getUserModel()->getCountUsersMyOrg($this->getUser()['user_organization_id']);
    $styles = [CSS . '/list_services.css'];
    $scripts = [JS . '/org_users.js'];
    require_once   './views/common/head.html';
    require_once   './views/common/header.html';
    require_once  './views/common/nav.php';
    require_once  './views/org_users.html';
    $this->helper->outputCommonFoot($scripts);
  }

  public function actionOrgupdateusers(){
    $text = "";
    $total = $this->getUserModel()->getCountUsersMyOrg($this->getUser()['user_organization_id']);
    $querySearch = "SELECT `user_id`, `user_name`, `user_surname`, `user_email`, `role_name` FROM `users`
                    LEFT JOIN `roles` ON `role_id` = `user_role_id`
                    WHERE `user_organization_id` = '" . $this->getUser()['user_organization_id'] . "'
                    ORDER BY `user_surname` DESC;";
    $queryAll = "SELECT `user_id`, `user_name`, `user_surname`, `user_email`, `role_name` FROM `users`
                    LEFT JOIN `roles` ON `role_id` = `user_role_id`
                    WHERE `user_organization_id` = '" . $this->getUser()['user_organization_id'] . "'
                    ORDER BY `user_surname` DESC LIMIT " . (int)$_POST['border'] . ", " . 20 . ";";
    $funFilter = 'Helper::searchFilterFriend';
    $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<tr><h3>В данной организации пока-что нет пользователей</h3></tr>");
    $counter = 0;
    foreach ($data as $item) {
      if ($item['user_id'] == $_COOKIE['uid']) {
        continue;
      }
      $text .= "<tr>";
      $text .= "<td><a href='./view/" . $item['user_id'] . "'>" . $item['user_name'] . " " . $item['user_surname'] .  "</a></td>";
      $text .= "<td>" . $item['user_email'] . "</td>";
      $text .= "<td>" . $item['role_name']  . "</td>";
      $text .= "<td><a class='btn' href='./exclude_user/" . $item['user_id'] . "'>Исключить</a></td>";
      $text .= "</tr>";
     $counter++;
     if (isset($_COOKIE['search']) && $counter>=50){
       break;
     }
    }
    if ($text == ""){
      $text = "<tr class='empty'><td>Не найдено ни одной записи</td></tr>";
    }
    echo $text;
  }

  public function actionOrgupdategroups(){
    $groupModel = new Group();
    $text = "";
    $total = $groupModel->getCountMyOrgGroup($this->getUser()['user_organization_id']);
    $querySearch = "SELECT `group_name`, `group_code`, `group_id`, `user_name`, `user_surname`, `group_user_id` FROM `groups`
                    LEFT JOIN `users` ON `user_id` = `group_user_id`
                    WHERE `group_organization_id` = '" . $this->getUser()['user_organization_id'] . "'
                    ORDER BY `group_name` DESC;";
    $queryAll = "SELECT `group_name`, `group_code`, `group_id`, `user_name`, `user_surname`, `group_user_id` FROM `groups`
                    LEFT JOIN `users` ON `user_id` = `group_user_id`
                    WHERE `group_organization_id` = '" . $this->getUser()['user_organization_id'] . "'
                  ORDER BY `group_name` DESC LIMIT " . (int)$_POST['border'] . ", " . 20 . ";";
    $funFilter = 'Helper::searchFilterGroup';
    $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter, "<tr><h3>В данной организации пока-что нет классов</h3></tr>");
    $counter = 0;
    foreach ($data as $item) {
      $text .= "<tr>";
      $text .= "<td><a href='./group/" . $item['group_id'] . "'>" . $item['group_name'] . "</a></td>";
      $text .= "<td>" . $item['group_code'] . "</td>";
      $text .= "<td><a href='./view/" . $item['group_user_id'] . "'>" . $item['user_name'] . " "  . $item['user_surname'] . "</a></td>";
      $text .= "<td><a class='btn' href='./check_group/" . $item['group_id'] . "'>Проверить класс</a></td>";
      $text .= "</tr>";
     $counter++;
     if (isset($_COOKIE['search']) && $counter>=50){
       break;
     }
    }
    if ($text == ""){
      $text = "<tr class='empty'><td>Не найдено ни одной записи</td></tr>";
    }
    echo $text;
  }
  public function actionCheck($data){
    $groupModel = new Group();
    $id_group = $data[0];
    if (!$groupModel->checkIfUserParticipant($id_group)) {
      $id_request = $groupModel->checkIfUserRequest($id_group);
      if ($id_request == 0) {
        $groupModel->addAccess($id_group);
      }else {
        $groupModel->update($id_request);
      }
    }
    header('Location: ../group/' . $id_group);
  }
  public function actionExclude($data){
    $id_user= $data[0];
    $this->getUserModel()->removeOrg($id_user);
    header('Location: ../org_users');
  }
}
