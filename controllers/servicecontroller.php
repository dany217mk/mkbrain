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
}
