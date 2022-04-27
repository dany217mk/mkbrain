<?php
class AuthController
{
  public $helper;
  public $userModel;
  public function __construct(){
    $this->helper = new Helper();
    $this->userModel = new User();
  }
  public function actionAuth(){
    if ($this->userModel->isAuth()) {
      header("Location: ./my");
    }
    $num = (int)date("Y");
    $genderModel = new Gender();
    $genders = $genderModel->getAll();
    $errors = array();
    $regActive = '';
    $authActive = '';
    $codeAuthActive = '';
    if(isset($_POST['login'])){
      $regActive = '';
      $authActive = 'active';
      $codeAuthActive = '';
      $email = $this->helper->escape_srting($_POST['email']);
      $password = $this->helper->escape_srting($_POST['password']);
      $hash = md5($password);
      $uid = $this->userModel->checkIfUserExistAuth($email, $hash);
      if($uid != -1){
        if ($uid == 0) {
          $errors['email'] = "Неверный адрес электронной почты или пароль!";
        } else {
          $this->userModel->setAuth($uid);
          header('location: my');
        }
      }else{
          $errors['email'] = "Похоже, ты еще не член клуба! Скорее регистрируйся!";
      }
    }
    if(isset($_POST['signup'])){
      $regActive = 'active';
      $authActive = '';
      $codeAuthActive = '';
      $name = $this->helper->escape_srting($_POST['name']);
      $surname = $this->helper->escape_srting($_POST['surname']);
      $email = $this->helper->escape_srting($_POST['email']);
      $gender = (int)$_POST['gender'];
      $day = $_POST['day'];
      if ((int)$day < 10){
        $day = "0" . $day;
      }
      $month = $_POST['month'];
      if ((int)$month < 10){
        $month = "0" . $month;
      }
      $year = $_POST['year'];
      $date_birth =  $year . "-" . $month . "-" . $day;
      if (!checkdate($month, $day, $year)) {
        $errors['date'] = "Выбранная дата недействительна.";
      }
      $password = $this->helper->escape_srting($_POST['password']);
      $cpassword = $_POST['cpassword'];
      if($password !== $cpassword){
          $errors['password'] = "Пароли не совпадают!";
      }

      $email_check = $this->userModel->checkIfUserExistAuth($email);
      if($email_check != -1){
          $errors['email'] = "Электронная почта, которую вы ввели, уже существует!";
      }

      if(count($errors) === 0){
          $password = md5($password);
          $code = rand(999999, 111111);
          $status = 2;
          $data_check = $this->userModel->add($name, $surname, $password, $email, $code, $status, $gender, $date_birth);
          if($data_check[0]){
              $uid = $data_check[1];
              $this->userModel->setAuth($uid);
               header('location: roles');
               exit();
          }else{
              $errors['db-error'] = "Ошибка при вставке данных в базу данных!";
          }
        }
      }
      if (isset($_POST['code_validation'])) {
        $regActive = '';
        $authActive = '';
        $codeAuthActive = 'active';
        $code = $this->helper->escape_srting($_POST['code']);
        $uid = $this->userModel->checkIfUserExistCodeAuth($code);
        if($uid != -1){
            $this->userModel->setAuth($uid);
            header('location: my');
        } else {
            $errors['code'] = "Такой код не найден";
        }
  }
    require_once './views/auth.html';
  }

  public function actionVkauth($data_get){
    $code = substr($data_get[0], 6);
    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token?client_id='.ID.'&display=page&redirect_uri='.URL.'&v=5.81&client_secret='.CODE.'&code='.$code.''), true);
    $data = json_decode(file_get_contents('https://api.vk.com/method/users.get?user_id='.$token['user_id'].'&access_token='.$token['access_token'].'&fields=uid,first_name,last_name,photo_big,sex,bdate&v=5.81'), true);
    if (!$data){
      header("Location: ./report/error");
    }
          $data = $data['response'][0];
          $data_vk = $this->userModel->checkIfUserExistVkAuth($data['id']);
          if (mysqli_num_rows($data_vk) > 0){
            $this->userModel->setAuth(mysqli_fetch_assoc($data_vk)['user_id']);
            header("Location: ../my");
          } else {
            $split = explode(".", $data['bdate']);
            $day = $split[0];
            $month = $split[1];
            $year = $split[2];
            if ((int)$day < 10){
              $day = "0" . $day;
            }
            if ((int)$month < 10){
              $month = "0" . $month;
            }
            $date_birth =  $year . "-" . $month . "-" . $day;
            $filename = $this->userModel->addImg();


          if(!file_put_contents("./assets/img_user/"  . $filename, file_get_contents($data['photo_big']))) {
            $filename = "";
          }
            $data_check = $this->userModel->addVk($data, $date_birth, $filename);
            if($data_check[0]){
              $uid = $data_check[1];
              $this->userModel->setAuth($uid);
               header('Location: ' . FULL_SITE_ROOT . '/roles');
               exit();
            } else {
              header('Location: ' . FULL_SITE_ROOT . '/report/error');
            }
          }
      }

}
