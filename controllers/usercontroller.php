<?php
  class UserController extends Controller
  {
    private $organizationModel;
    public function __construct(){
      parent::__construct();
      $this->organizationModel = new Organization();
    }
    public function actionRole(){
      if (!$this->getIsAuth()) {
          header('location: ./report/noreg');
      }
      if ($this->getUser()['user_role_id'] != NULL && $this->getUser()['user_role_id'] != '') {
        header("Location: ./my");
      }
      $title = "Роль на портале";
      $styles = ['./assets/css/roles.css'];
      $roleModel = new Role();
      $roles = $roleModel->getAll();
      require_once './views/common/head.html';
      require_once './views/roles.html';
    }

    public function actionRoleedit($data){
      if (!$this->getIsAuth()) {
          header('location: ./report/noreg');
      }
      if ($this->getUser()['user_role_id'] != NULL && $this->getUser()['user_role_id'] != '') {
        header("Location: ../my");
      }
      $roleNum = $data[0];
      $this->getUserModel()->addRole($roleNum);
      header("Location: ../my");
    }

    public function actionLogout(){
      $this->getUserModel()->logout();
    }

    public function actionMy(){
      $friendModel = new Friend();
      $title = $this->getUser()['user_name'] . ' ' . $this->getUser()['user_surname'];
      $styles = [CSS . '/my.css'];
      $scripts = [JS . '/my.js'];
      $split = explode("-", $this->getUser()['user_dob']);
      $dob = $split[2] . "." . $split[1] . "." . $split[0];
      $row = $friendModel->countFriends();
      $favorites = $this->getUserModel()->getFavoritesTests();
      $totalFriend =  $row['COUNT(*)'];
      $totalGroup = $this->getUserModel()->getCountGroups();
      $totalTest = $this->getUserModel()->getCountTests();
      require_once   './views/common/head.html';
      require_once   './views/common/header.html';
      require_once  './views/common/nav.php';
      require_once  './views/my.html';
      $this->helper->outputCommonFoot($scripts);
    }

    public function actionSettings(){
      $title = "Настройки";
      $styles = [CSS . '/settings.css'];
      $scripts = [JS . '/settings.js'];
      $row = $this->getUserModel()->countOrganization();
      $totalOrg =  $row['COUNT(*)'];
      $checkPrivacy= "";
      if ($this->getUser()['user_privacy'] == 1) {
        $checkPrivacy = "checked";
      }
      $org = $this->getUser()['organization_name'];
      if ($org == '') {
        $org = "Вы не состоите ни в одной организации";
      }
      require_once   './views/common/head.html';
      require_once   './views/common/header.html';
      require_once  './views/common/nav.php';
      require_once  './views/settings.html';
      $this->helper->outputCommonFoot($scripts);
    }

    public function actionDeletefavorite(){
      $res = $this->getUserModel()->deleteFavorite($_POST['id_test']);
      if ($res) {
        echo "success";
      } else {
        echo "error";
      }
    }

    public function actionDescribe(){
      $desc = "";
      $desc =  $this->helper->escape_srting($_POST['describe']);
      $res = $this->getUserModel()->updateDescribe($desc);
      if ($res) {
        echo "success";
      } else{
        echo "error";
      }
    }
    public function actionAddorg(){
      if (isset($_POST['id'])){
            $res = $this->getUserModel()->updateOrg($_POST['id']);
            if ($res) {
              echo "success";
            } else{
              echo "error";
            }
        }
    }

    public function actionExitorg(){
        $res = $this->getUserModel()->emptyOrg();
        if ($res) {
          header("Location: ./report/success");
        } else {
          header("Location: ./report/error");
        }
    }



    public function actionUpdateprivacy(){
      if (isset($_POST['private'])) {
        if ($_POST['private'] == 'true') {
          $private = 1;
        } else {
          $private = 0;
        }
        $res = $this->getUserModel()->updatePrivacy($private);
        if ($res) {
          echo "Данные успешно отправлены";
        } else {
          echo "Ошибка";
        }
    }
    }

    public function actionUpdateorg(){
        $text = "";
        $query = "SELECT COUNT(*) FROM `organizations`;";
        $total =  $this->organizationModel->getCount();
        $querySearch = $query = "SELECT * FROM `organizations`
           LEFT JOIN `users` ON `user_id` = '" . $_COOKIE['uid'] . "' WHERE (`user_organization_id` IS NULL) OR  (`user_organization_id` != `organization_id`)
           ORDER BY `organization_id` DESC";
        $queryAll = "SELECT * FROM `organizations`
           LEFT JOIN `users` ON `user_id` = '" . $_COOKIE['uid'] . "' WHERE (`user_organization_id` IS NULL) OR  (`user_organization_id` != `organization_id`)
           ORDER BY `organization_id` DESC LIMIT " . (int)$_POST['border'] . ", " . 20 . ";";
           $funFilter = 'Helper::searchFilterOrg';
           $data  = $this->helper->outputSmt($total, $queryAll, $querySearch, $funFilter);
        $counter = 0;
        foreach ($data as $item) {
          $text .= "<tr id='" . $item['organization_id'] . "'>";
          $text .= "<td>" . $item['organization_name'] . "</td>";
          $text .= '<td><button onclick="addOrganization(\'' . $item['organization_id'] . '\', \'' . $item['organization_name'] . '\')">Присоедениться к организации</button></td>';
          $text .= "</tr>";
          if (isset($_COOKIE['search']) && $counter>=20){
            break;
          }
        }
        if ($text == ""){
          $text = "<tr><td colspan='2' class='empty'><h4>Не найдено ни одной записи</h4></td></tr>";
        }
        echo $text;
    }

      public function actionUpload(){
        if (!is_null($this->getUserModel()->getImg()) && $this->getUserModel()->getImg() != "") {
          unlink('./assets/img_user/' . $this->getImg()['user_img']);
        }
        $file = 'img';
        $filename = $this->getUserModel()->addImg();
        if (move_uploaded_file($_FILES[$file]['tmp_name'], './assets/img_user/' . $filename)){
          $resImg = $this->getUserModel()->updateImg($filename);
          if ($resImg == 1) {
            header("Location: ./report/successUpload");
          }
      } else {
        echo "error";
        exit;
        //header("Location: ./report/errorUpload");
      }
    }
    public function actionDeleteimg(){
        $boolDel = false;
        if ($this->getUserModel()->existImg() == IMG_DEFAULT) {
          header("Location: ./report/noquery");
        }
        if ($this->getUserModel()->getImg() != "" && file_exists('./assets/img_user/' . $this->getUser()['user_img'])) {
         unlink('./assets/img_user/' . $this->getUser()['user_img']);
         $this->getUserModel()->deleteimg();
         $boolDel = true;
        }
        if ($boolDel) {
          header("location: ./report/success");
        } else {
          header("location: ./report/error");
        }
    }
}
