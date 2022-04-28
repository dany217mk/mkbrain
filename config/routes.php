<?php
$routes = array(
  'TestController' => array(
    'testview/([0-9]+)' => 'testview/$1',
    'tests' => 'tests',
    'test/([0-9]+)' => 'test/$1',
    'updatefavorite' => 'updatefavorite',
    'updatetests' => 'updatetests',
    'constructor' => 'constructor',
    'sendques' => 'sendques',
    'infoinserting' => 'infoinserting',
    'formchecking' => 'formchecking',
    'timechecking' => 'timechecking',
    'checktest/([0-9]+)' => 'checktest/$1'
  ),
  'GroupController' => array(
    'groups' => 'groups',
    'group-requests' => 'requests',
    'updategroups' => 'updategroups',
    'updategrouprequests' => 'updategrouprequests',
    'requestgroupdeleted' => 'requestgroupdeleted',
    'group-search' => 'search',
    'requestadding' => 'requestadding',
    'group/([0-9]+)' => 'group/$1',
  ),
  'FriendController' => array(
    'friends' => 'friends',
    'updatefriends' => 'update_friends',
    'friend_deleted' => 'frienddeleted',
    'requests' => 'requests',
    'updaterequests' => 'updaterequests',
    'friendremove' => 'frienddeleted',
    'friendaccepting' => 'friendaccept',
    'myrequests' => 'myrequests',
    'request_deleted' => 'frienddeleted',
    'updatemyrequests' => 'updatemyrequests',
    'view/([0-9]+)' => 'view/$1',
    'friendaction' => 'friendaction',
    'friend-search' => 'search',
    'updatesearchfriend' => 'updatesearchfriend',
    'friendadding' => 'friendadding'
  ),
  'UserController' => array(
    'roles' => 'role',
    'role/([0-9]+)' => 'roleedit/$1',
    'my' => 'my',
    'logout' => 'logout',
    'settings' => 'settings',
    'describe' => 'describe',
    'send__setting_form' => 'sendsettingform',
    'update_privacy' => 'updateprivacy',
    'exit_org' => 'exitorg',
    'update_org' => 'updateorg',
    'add_org' => 'addorg',
    'upload_file' => 'upload',
    'delete_img' => 'deleteimg'
  ),
  'MessageController' => array(
    'addmsg' => 'addmsg',
    'getchat' => 'getchat',
    'readmsg' => 'readmsg',
    'im' => 'im',
    'chat/([0-9]+)' => 'chat/$1',
    'updateim' => 'updateim',
  ),
  'AuthController' => array(
    'vkauth/?code=([a-z]+)' => 'vkauth/$1',
    'auth' => 'auth',
  ),
  'MainController' => array(
      'report/([a-z]+)' => 'report/$1',
      'privacy' => 'privacy',
      '' => 'index'
    )
);
