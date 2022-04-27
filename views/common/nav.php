<nav class="sidebar">
  <div class="logo-details">
      <div class="logo_name">Меню</div>
      <i class='fa fa-bars' id="btn" ></i>
  </div>
<?php
  require_once './models/model.php';
  $modelMenu = new Model();
  if (isset($this->user)){
    $border = (int)$this->getUser()['user_role_id'] - 1;;
    if ($border < 0) {
      $border=0;
    }
   $query = "SELECT * FROM `menu` WHERE `menu_active` = 1 AND `menu_access` BETWEEN 0 AND $border;";
 } else{
   $query = "SELECT * FROM `menu` WHERE `menu_active` = 1 AND `menu_access` = -1;";
 }
  $res = $modelMenu->returnActionQuery($query);


  $noteMsg = "";

      echo "<ul class='nav-list'>";
      while (($row = $res->fetch_assoc()) != false) {
        echo "<li>";
        echo "<a href='" . $row['menu_link'] . "'>";
        if ($row['menu_name'] == "Письма") {
          echo '<i class="' . $row['menu_favicon'] . ' ' . $noteMsg . '"></i>';
        } else {
          echo '<i class="' . $row['menu_favicon'] . '"></i>';
        }
        echo  '<span class="links_name">' . $row['menu_name'] . '</span>';
        echo "</a>";
        echo '<span class="tooltip">' . $row['menu_tooltip'] . '</span>';
        echo "</li>";
      }
      echo "</ul>";
 ?>
</nav>
<nav class="sidebar_mobile">
  <? if (isset($this->user)): ?>
  <ul>
    <li><a href="<?= FULL_SITE_ROOT ?>/my"><span class="icon"><i class="fas fa-newspaper" aria-hidden="true"></i></span><span class="title">Новости</span></a></li>
    <li><a href="<?= FULL_SITE_ROOT ?>/services"><span class="icon"><i class="fa fa-list" aria-hidden="true"></i></span><span class="title">Сервисы</span></a></li>
    <li><a href="<?= FULL_SITE_ROOT ?>/im"><span class="icon"><i class="fas fa-comments" aria-hidden="true"></i></span><span class="title">Сообщения</span></a></li>
    <li><a href="<?= FULL_SITE_ROOT ?>/group"><span class="icon"><i class="fas fa-graduation-cap" aria-hidden="true"></i></span><span class="title">Классы</span></a></li>
    <li><a href="<?= FULL_SITE_ROOT ?>/tests"><span class="icon"><i class="fas fa-chalkboard-teacher" aria-hidden="true"></i></span><span class="title">Тесты</span></a></li>
  </ul>
  <? else: ?>
  <ul>
    <li><a href="<?= FULL_SITE_ROOT ?>"><span class="icon"><i class="fas fa-home" aria-hidden="true"></i></span><span class="title">Главная</span></a></li>
  </ul>
  <? endif; ?>
</nav>
