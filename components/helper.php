<?php
/*
  __ MKStudio __
*/
class Helper
{

  private $con;

  public function __construct()
  {
    $this->con = DB::getConnection();
  }

  public function outputCommonFoot($scripts=[]){
    require_once   './views/common/footer.html';
    require_once   './views/common/foot.html';
  }



  public function generationToken($size = 32){
      $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $token = "";
      for ($i=0; $i<$size; $i++) {
        $rnd = rand(0, strlen($chars)-1);
        $token .= substr($chars, $rnd, 1);
      }
      return $token;
  }

    public function checkImg($val)
        {
          global $bool;
          $allowed = array('gif', 'png', 'jpeg', 'jpg');
          $filename = $_FILES[$val]['name'];
          $ext = pathinfo($filename, PATHINFO_EXTENSION);
          if (!in_array($ext, $allowed)) {
            header("Location: " . FULL_SITE_ROOT . "/answer/errorUpload");
            $bool = false;
          } else {
            if ($_FILES[$val]['size'] > 10000000){
              header("Location: " . FULL_SITE_ROOT . "/answer/errorUpload");
              $bool = false;
            }
          }
        }

    public function escape_srting($val){
      return htmlentities(mysqli_real_escape_string($this->con, $val));
    }

    public function searchFilterOrg($row) {
          $filter = $row['organization_name'];
                     return
          mb_strstr (mb_strtolower($filter), mb_strtolower(trim($_COOKIE['search'])));
      }

      public function searchFilterFriend($row) {
        $fio = $row['user_surname'] . " " . $row['user_name'];
                      return
                      mb_strstr (mb_strtolower($row['user_name']), mb_strtolower(trim($_COOKIE['search']))) ||
                      mb_strstr (mb_strtolower($row['user_surname']), mb_strtolower(trim($_COOKIE['search']))) ||
            mb_strstr (mb_strtolower($fio), mb_strtolower(trim($_COOKIE['search'])));
       }

       public function searchFilterRequest($row){
         $fio = $row['user_surname'] . " " . $row['user_name'];
      		return
      		mb_strstr (mb_strtolower($row['user_name']), mb_strtolower(trim($_COOKIE['search']))) ||
      		mb_strstr (mb_strtolower($row['user_surname']), mb_strtolower(trim($_COOKIE['search']))) ||
          mb_strstr (mb_strtolower($fio), mb_strtolower(trim($_COOKIE['search'])));
       }

       public function searchFilterMyRequest($row){
         $fio = $row['user_surname'] . " " . $row['user_name'];
         return
         mb_strstr (mb_strtolower($row['user_name']), mb_strtolower(trim($_COOKIE['search']))) ||
         mb_strstr (mb_strtolower($row['user_surname']), mb_strtolower(trim($_COOKIE['search']))) ||
         mb_strstr (mb_strtolower($fio), mb_strtolower(trim($_COOKIE['search'])));
       }

       public function searchFilterFriendSearch($row){
         $fio = $row['user_surname'] . " " . $row['user_name'];
    		return
    		mb_strstr (mb_strtolower($row['user_name']), mb_strtolower(trim($_COOKIE['search']))) ||
    		mb_strstr (mb_strtolower($row['user_surname']), mb_strtolower(trim($_COOKIE['search']))) ||
        mb_strstr (mb_strtolower($fio), mb_strtolower(trim($_COOKIE['search'])));
       }

       public function searchFilterIm($row){
         $fio = $row['user_surname'] . " " . $row['user_name'];
     		return
     		mb_strstr (mb_strtolower($row['user_name']), mb_strtolower(trim($_COOKIE['search']))) ||
     		mb_strstr (mb_strtolower($row['user_surname']), mb_strtolower(trim($_COOKIE['search']))) ||
        mb_strstr (mb_strtolower($fio), mb_strtolower(trim($_COOKIE['search'])));
       }

    public static function mySortMsgId($a, $b) {
   		$field = "msg_id";
         if ($a[$field] < $b[$field]) {
                 return 1;
             } else if ($a[$field] > $b[$field]) {
                 return -1;
             } else {
                 return 0;
         }
   	}

    public function searchFilterTest($row){
  		return
  		mb_strstr (mb_strtolower($row['test_name']), mb_strtolower(trim($_COOKIE['search'])));
    }
    public function searchFilterGroup($row){
  		return
  		mb_strstr (mb_strtolower($row['group_name']), mb_strtolower(trim($_COOKIE['search'])));
    }

    public function outputSmt($total, $queryAll, $querySearch, $funFilter, $notFoundMsg="<div class='empty'>Здесь пока-что ничего нет</div>"){
      $query = "";
      if (isset($_COOKIE['search']) && $_COOKIE['search']) {
        $query = $querySearch;
      } else{
         $query = $queryAll;
      }
      $model = new Model();
      $res = $model->returnActionQuery($query);
      if(($res == false || mysqli_num_rows($res) <= 0)){
          echo $notFoundMsg;
          exit;
      }
      if ($_POST['border'] == $total && !isset($_COOKIE['search'])) {
        echo $notFoundMsg;
        exit;
      }
      $data = mysqli_fetch_all($res, MYSQLI_ASSOC);

      if ($funFilter != ''){
        if (isset($_COOKIE['search']) && $_COOKIE['search']) {
           $data = array_filter($data, $funFilter);
        }
      }
       return $data;
    }





}
