<?php
class Model
{
    private $con;
      private $helper;
      public function __construct()
      {
        $this->con = DB::getConnection();
        $this->helper = new Helper();
      }
      public function getHelper(){
        return $this->helper;
      }

      public function returnAllNum($query){
          $res = mysqli_query($this->con, $query);
          return mysqli_fetch_all($res,  MYSQLI_NUM);
      }
      public function returnAllAssoc($query){
          $res = mysqli_query($this->con, $query);
          return mysqli_fetch_all($res,  MYSQLI_ASSOC);
      }
      public function returnAssoc($query){
          $res = mysqli_query($this->con, $query);
          return mysqli_fetch_assoc($res);
      }
      public function actionQuery($query){
          mysqli_query($this->con, $query);
      }
      public function returnLastId($query){
          mysqli_query($this->con, $query);
          return mysqli_insert_id($this->con);;
      }
      public function returnNumRows($query){
          $res = mysqli_query($this->con, $query);
          return mysqli_num_rows($res);;
      }
      public function returnActionQuery($query){
          return mysqli_query($this->con, $query);
      }
      public function getLastId(){
        return mysqli_insert_id($this->con);;
      }
}
