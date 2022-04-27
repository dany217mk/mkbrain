<?php
  class Controller{
    public $helper;
    private  $isAuth = false;
    private  $userModel;
    public $user;
    public function __construct(){
      $this->helper = new Helper();
      $this->userModel = new User();
      $this->isAuth = $this->userModel->isAuth();
      if ($this->isAuth) {
        $this->user = $this->userModel->getUser();
        if ($this->getUser()['user_role_id'] == NULL || $this->getUser()['user_role_id'] == '') {
          if ($_SERVER['REQUEST_URI'] != ROLE_PAGE) {
            header("Location: " . FULL_SITE_ROOT . "/roles");
          }
        }
      } else {
        header("Location: " . FULL_SITE_ROOT . "/report/undefinedUser");
      }
    }
    public function getIsAuth(){
      return $this->isAuth;
    }
    public function getUserModel(){
      return $this->userModel;
    }
    public function getUser(){
      return $this->user;
    }
  }
