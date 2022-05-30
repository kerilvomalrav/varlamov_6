<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.

$user = 'u47744';
$pass = '9352325';
$db = new PDO('mysql:host=localhost;dbname=u47744', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
$sel = $db->query("SELECT login FROM lopadmin"); // Таблицу Admin надо создать и запихнуть туда логин и хэш пароля (md5) и запомнить, по ним вы будете входить
    foreach($sel as $el)
      $login=$el['login'];
  $sel = $db->query("SELECT pass FROM lopadmin");
    foreach($sel as $el)
      $pas=$el['pass'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();

  $errors = array();
  $errors['error'] = !empty($_COOKIE['er_error']);
  if ($errors['error']) {    
    setcookie('er_error', '', 100000);
    $messages[] = '<div class="error">id редактируемого и id удаляемого пользователя не могут совпадать.</div>';
  }

if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != $login ||
    md5($_SERVER['PHP_AUTH_PW']) != $pas) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}
print('Вы успешно авторизовались и видите защищенные паролем данные.');

$res=$db->query("SELECT * FROM application_2");
$sp1=$db->query("SELECT count(*) from ultimate where nomer = 1"); // меняете на своё
$sp2=$db->query("SELECT count(*) from ultimate where nomer= 2"); // меняете на своё
$sp3=$db->query("SELECT count(*) from ultimate where nomer = 3"); // меняете на своё
$sp4=$db->query("SELECT count(*) from ultimate where nomer = 4"); // меняете на своё


  ?>
  <style>
/* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
.error {
  border: 2px solid red;
}
    </style>
 
<?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>
<h2>Информация о пользователях</h2>
  <table border="2">
    <tr>
      <td>ID</td>
      <td>ФИО</td>
      <td>E-mail</td>
      <td>Год рождения</td>
      <td>Биография</td>
      <td>Пол</td>
      <td>Количество конечностей</td>
    </tr>
  <?php foreach($res as $el){ ?>
    <tr>
      <td><?php print($el['id']); ?></td> <?php// меняете на своё ?>
      <td><?php print($el['name']); ?></td> <?php// меняете на своё ?>
      <td><?php print($el['email']); ?></td> <?php// меняете на своё ?>
      <td><?php print($el['date']); ?></td> <?php// меняете на своё ?>
      <td><?php print($el['bio']); ?></td>  <?php// меняете на своё ?>       
      <td><?php print($el['pol']); ?></td> <?php// меняете на своё ?>
      <td><?php print($el['hands']); ?></td> <?php// меняете на своё ?>
      <?php } ?>
    </tr>
  </table>
  <h2>Статистика по суперспособностям</h2>
  <table border="2">
    <tr>
      <td>Бессмертие</td> <?php// меняете на своё ?>
      <td>Прохождение сквозь стены</td> <?php// меняете на своё ?>
      <td>Левитация</td> <?php// меняете на своё ?>
    </tr>
     
    <tr>
      <td><?php foreach($sp1 as $el)
      print($el['count(*)']);
       ?></td>
      <td><?php foreach($sp2 as $el)
      print($el['count(*)']); ?></td>
      <td><?php foreach($sp3 as $el)
      print($el['count(*)']); ?></td>
      <td><?php foreach($sp4 as $el)
      print($el['count(*)']); ?></td>
    </tr>
  </table>

  <br/>

  <form action="" method="post">
    Введите id для редактирования
    <br/>
    <input name="edit" /> 
    <br/><br/>
    Введите id для удаления
    <br/>
    <input name="del" /> 
    <br/><br/>
    <input type="submit" value="Подтвердить" />
  </form>
  <?php


}else
{
  $errors = FALSE;
  if($_POST['edit']==$_POST['del']) { // Если id для удаления совпадает с id для редактирования выдаем ошибочную куку
    setcookie('er_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

    if ($errors) {
      header('Location: admin.php');
      exit();
    }
    else {
      setcookie('er_error', '', 100000);
    }

    if (!empty($_POST['del'])){ // это если поле для удаления не пусто
      $idd=(int)$_POST['del'];
      $db->query("DELETE FROM ultimate WHERE id = $idd"); // меняете на своё
      $db->query("DELETE FROM loginparol WHERE id = $idd"); // меняете на своё
      $db->query("DELETE FROM application_2 WHERE id = $idd"); // меняете на своё
      header('Location: admin.php');
    }


    if (!empty($_POST['edit'])){ // Это если поле для редактирования не пусто
    session_start();
    $_SESSION['uid']=(int)$_POST['edit'];
    $_SESSION['login']='Admin'; // можете написать что хотите, главное чтоб не было пусто
    header('Location: ./');
    }

}

// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********
