<?php
final class DB
{
    private static  $connection;

    public  function __construct()
    {
        global $db;
        $connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['db_name']);
        mysqli_set_charset($connect, $db['charset']);
        self::$connection = $connect;
    }
    public static function getConnection(){
      if (!self::$connection) {
        new DB();
      }
        return self::$connection;
    }

    private function __clone(){

    }
    public function __sleep(){

    }
    public function __wakeup(){

    }
}
