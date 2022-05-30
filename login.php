<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  session_destroy();
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
  header('Location: ./');
}

$massages=array();
$errors=array();
$errors['avtor']=!empty($_COOKIE['avtor_error']);
if($errors['avtor']){
  setcookie('avtor_error','',100000);
  $massages[]='<div class="error">not authorized</div>';
}
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<style>
/* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
.error {
  border: 10px solid red;
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
?>

<form action="" method="post">
  <input name="login" value="Логин" <?php if ($errors['avtor']) {print 'class="error"';} ?>/>
  <input name="pass" value="Пароль" <?php if ($errors['avtor']) {print 'class="error"';} ?>/>
  <input type="submit" value="Войти" />
</form>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
$errors=FALSE;
$login=$_POST['login'];
$pass=md5($_POST['pass']); 
$user = 'u47744';
$pas = '9352325';
$db = new PDO('mysql:host=localhost;dbname=u47744', $user, $pas, array(PDO::ATTR_PERSISTENT => true));
$result=$db->query("SELECT login FROM loginparol WHERE login=$login");
foreach($result as $el)
$login2=$el['login'];
$result=$db->query("SELECT parol FROM loginparol WHERE login=$login");
foreach($result as $el)
$pass2=$el['parol'];
if(!empty($login2)&&!empty($pass2)&&$pass==$pass2)
{
// TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибках.

  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  $pass3=$db -> quote($pass2);
  $result=$db -> query("SELECT id FROM loginparol WHERE login=$login AND parol=$pass3");
  foreach($result as $el)
  $id=(int)$el['id'];

  // Записываем ID пользователя.
  $_SESSION['uid'] = $id;
}
else{
  $errors=TRUE;
  setcookie('avtor_error','1',time()+24*24*60);
}
if($errors){
  header('Location: login.php');
  exit();
}
else
setcookie('avtor_error','',100000);
  

  // Делаем перенаправление.
  header('Location: ./');
}
