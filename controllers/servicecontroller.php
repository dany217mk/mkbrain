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
}
