<?php
  class User extends Model{

    public function checkIfUserExistAuth($email, $password='NONE'){
      $query = "SELECT `user_id`, `user_password` FROM `users` WHERE `user_email` = '$email'";
      $res = $this->returnActionQuery($query);
      if (mysqli_num_rows($res) == 0){
        return -1;
      }
      $mas = mysqli_fetch_assoc($res);
      if ($password != $mas['user_password']) {
        return 0;
      }
      return $mas['user_id'];
    }

    public function checkIfUserExistCodeAuth($code){
      $query = "SELECT * FROM `users` WHERE `user_unique` = '$code'";
      $res = $this->returnActionQuery($query);
      if (mysqli_num_rows($res) == 0){
        return -1;
      }
      $mas = mysqli_fetch_assoc($res);
      return $mas['user_id'];
    }

    public function checkIfUserExistVkAuth($vk_id){
      $query = "SELECT * FROM `users` WHERE `user_vk_id` = '" . $vk_id . "'";
      return $this->returnActionQuery($query);
    }
    public function addImg(){
      $upload_path = IMG_USER . '/';
      $filename = $this->getHelper()->generationToken() . ".png";

      $allow = false;
      $counterIter = 0;

      while (!$allow) {
        $counterIter++;
        $fetch_mas = $this->check_user_img($filename);
        $counter =  $fetch_mas['COUNT(*)'];
        if ($counter > 0) {
          $allow = false;
        } else {
          $allow = true;
        }
      if (!$allow){
        $filename = $this->getHelper()->generationToken() . ".png";
      }
    }
      return $filename;
    }

    public function updateImg($filename){
      $query = "UPDATE `users` SET `user_img` = '" . $filename . "' WHERE `user_id` = '" . $_COOKIE['uid'] . "'";
      return $this->returnActionQuery($query);
    }

    public function addVk($data, $date_birth, $filename){
      $query = "INSERT INTO `users` (`user_name`, `user_surname`, `user_vk_id`, `user_gender_id`, `user_dob`, `user_code`, `user_status_id`, `user_img`)
                      values('".$data['first_name']."', '".$data['last_name']."', '" . $data['id'] . "', '".$data['sex']."', '$date_birth', 0, 2, '$filename')";
      $res = $this->returnActionQuery($query);
      $uid = $this->getLastId();
      return array($res, $uid);
    }

    public function check_user_img($filename){
      $query = "SELECT COUNT(*) FROM `users` WHERE `user_img` = '" . $filename . "'";
      $res = $this->returnActionQuery($query);
      return mysqli_fetch_assoc($res);
    }

    public function add($name, $surname, $password, $email, $code, $status, $gender, $date_birth){
      $query = "INSERT INTO users (user_name, user_surname, user_password, user_email, user_code, user_status_id, user_gender_id, user_dob)
                      values('$name', '$surname', '$password', '$email', '$code', '$status', '$gender', '$date_birth')";
     $res = $this->returnActionQuery($query);
     $uid = $this->getLastId();
     return array($res, $uid);
    }

    public function setAuth($uid){
        $token = $this->getHelper()->generationToken();
        $timeToken = time() + 1800;
        $query = "INSERT INTO `connects` (`connect_user_id`, `connect_token`, `connect_time`) VALUES ($uid, '$token', FROM_UNIXTIME('$timeToken'))";
        $this->actionQuery($query);
        setcookie('uid', $uid, time() + 2*24*3600, '/');
        setcookie('t', $token, time() + 2*24*3600, '/');
        setcookie('tt', $timeToken, time() + 2*24*3600, '/');
    }

    public  function isAuth(){
        if (isset($_COOKIE['uid']) && isset($_COOKIE['t']) && isset($_COOKIE['tt'])){
            $timeToken = $_COOKIE['tt'];
            $query = "SELECT * FROM `connects` WHERE `connect_user_id` = '" . $_COOKIE['uid'] . "' and `connect_token` = '" . $_COOKIE['t'] . "'";
            $res = $this->returnActionQuery($query);
            if (mysqli_num_rows($res) > 0){
                if (time() > $_COOKIE['tt']){
                    $token = $this->getHelper()->generationToken();
                    $timeToken = time() + 1800;
                    $query = "UPDATE `connects` SET `connect_token` = '$token', `connect_time` = FROM_UNIXTIME('$timeToken') WHERE `connect_user_id` = '" . $_COOKIE['uid']  . "' and `connect_token` = '" . $_COOKIE['t']  . "';";
                    parent::actionQuery($query);
                    setcookie('uid', $_COOKIE['uid'], time() + 2*24*3600, '/');
                    setcookie('t', $token, time() + 2*24*3600, '/');
                    setcookie('tt', $timeToken, time() + 2*24*3600, '/');
                }
                return true;
            } else {
              $this->logout();
            }
        }
        return false;
    }

    public function getUser(){
      $query = "SELECT *, `role_name`, `gender_name`, `organization_name` FROM `users`
      LEFT JOIN `roles` ON `role_id` = `user_role_id`
      LEFT JOIN  `genders` ON `gender_id` = `user_gender_id`
      LEFT JOIN `organizations` ON `organization_id` = `user_organization_id`
       WHERE `user_id` = '" . $_COOKIE['uid'] . "'";
      return $this->returnAssoc($query);
    }


    public function logout(){
        $query = "DELETE FROM `connects` WHERE `connect_user_id` = '" . $_COOKIE['uid'] . "'";
        $this->actionQuery($query);
        setcookie('uid', '', -1, '/');
        setcookie('t', '', -1, '/');
        setcookie('tt', '', -1, '/');
        header('Location: ' . FULL_SITE_ROOT);
    }

    public function addRole($role_id){
      $query = "UPDATE `users` SET `user_role_id`= $role_id WHERE `user_id` = '" . $_COOKIE['uid'] . "'";
      $this->actionQuery($query);
    }

    public function getImg(){
      $query = "SELECT `user_img` FROM `users` WHERE `user_id` = '" . $_COOKIE['uid'] . "'";
      $res = $this->returnAssoc($query);
      return $res['user_img'];
    }

    public function existImg(){
      if (is_null($this->getImg()) || $this->getImg() == "") {
         return IMG_DEFAULT;
      } else {
          return '<img src="' . IMG_USER . '/' . $this->getImg() . '">';
       }
     }

     public function deleteImg(){
       $query = "UPDATE `users` SET `user_img` = '' WHERE `user_id` = '" . $_COOKIE['uid'] . "'";
       $this->actionQuery($query);
     }

     public function updateDescribe($desc){
       $query = "UPDATE users SET user_describe = '" . trim($desc) . "' WHERE user_id = '" . $_COOKIE['uid'] . "'";
        return $this->returnActionQuery($query);
     }

     public function countOrganization(){
       $query = "SELECT COUNT(*) FROM `organizations`;";
       return $this->returnAssoc($query);
     }

     public function updatePrivacy($private){
       $query = "UPDATE users SET user_privacy = '$private' WHERE user_id = '" . $_COOKIE['uid'] . "'";
        return $this->returnActionQuery($query);
     }

     public function updateOrg($org){
       $query = "UPDATE users SET user_organization_id = '$org' WHERE user_id = '" . $_COOKIE['uid'] . "'";
        return $this->returnActionQuery($query);
     }

     public function emptyOrg(){
       $query = "UPDATE `users` SET `user_organization_id` = NULL WHERE `user_id` = '" . $_COOKIE['uid'] . "';";
       return $this->returnActionQuery($query);
     }

  }
