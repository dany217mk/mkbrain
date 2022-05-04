<?php
    define("SITE_ROOT", "/mkbrain");
    define("FULL_SITE_ROOT", "http://localhost" . SITE_ROOT);
    define("FILE_ROOT", "/mkbrain");
    define("FULL_FILE_ROOT", "D://xampp/htdocs" . FILE_ROOT);
    define("ASSETS", FULL_SITE_ROOT . "/assets");
    define("JS", ASSETS . "/js");
    define("CSS", ASSETS . "/css");
    define("IMG", ASSETS . "/img");
    define("LIBS", ASSETS . "/libs");
    define("REQUEST_URI_EXIST", "/mkbrain/");
    define("SERVER_NAME", 'MKBrain');
    define("CONTACT_ADMIN", 'development');
    define("ADMIN_EMAIL", 'checkbraininfo@yandex.ru');
    define("ID", "8006118");
    define("CODE", "7Oava0Rs0mDOhxUoQ5hw");
    define("URL", FULL_SITE_ROOT . "/vkauth/");
    define("IMG_USER", ASSETS . "/img_user");
    define("IMG_RECORD", ASSETS . "/img_record");
    define('IMG_GROUP', ASSETS . "/img_group");

    define("IMG_DEFAULT", "<img src='" . IMG . "/profile.png'>");
    define("ROLE_PAGE", '/mkbrain/roles');
    $db = array(
           'host' => 'localhost',
           'user' => 'root',
           'password' => '',
           'db_name' => 'mkbrain',
           'charset' => 'utf8'
       );
