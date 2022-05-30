<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
    // Если в куках есть пароль, то выводим сообщение.
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }


  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['date'] = !empty($_COOKIE['date_error']);
  $errors['ultimate'] = !empty($_COOKIE['ultimate_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['pol'] = !empty($_COOKIE['pol_error']);
  $errors['hands'] = !empty($_COOKIE['hands_error']);

  // TODO: аналогично все поля.

  // Выдаем сообщения об ошибках.
  if ($errors['name']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('name_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните корректно имя.</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Заполните корректно email.</div>';
  }
  if ($errors['date']) {
    setcookie('date_error', '', 100000);
    $messages[] = '<div class="error">Вы забыли заполнить дату.</div>';
  }
  if ($errors['ultimate']) {
    setcookie('ultimate_error', '', 100000);
    $messages[] = '<div class="error">Вы забыли выбрать суперспособность.</div>';
  }
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error">Вы забыли рассказать о себе.</div>';
  }
  if ($errors['pol']) {
    setcookie('pol_error', '', 100000);
    $messages[] = '<div class="error">Вы забыли указать пол.</div>';
  }
  if ($errors['hands']) {
    setcookie('hands_error', '', 100000);
    $messages[] = '<div class="error">Вы забыли указать количество конечностей.</div>';
  }
  


  // TODO: тут выдать сообщения об ошибках в других полях.

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  
    // TODO: аналогично все поля.
  $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
  $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
  $values['date'] = empty($_COOKIE['date_value']) ? '' : strip_tags($_COOKIE['date_value']);
  $values['ultimate'] = empty($_COOKIE['ultimate_value']) ? '' : strip_tags($_COOKIE['ultimate_value']);
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
  $values['pol'] = empty($_COOKIE['pol_value']) ? '' : strip_tags($_COOKIE['pol_value']);
  $values['hands'] = empty($_COOKIE['hands_value']) ? '' : strip_tags($_COOKIE['hands_value']);
  $err=false;
  foreach($errors as $ell)
  if ($ell==true)
  $err=true;
  if ($err==false && !empty($_COOKIE[session_name()]) &&
  session_start() && !empty($_SESSION['login'])) {
$user = 'u47744';
$pass = '9352325';
$db = new PDO('mysql:host=localhost;dbname=u47744', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
$uid=$_SESSION['uid'];
$res=$db->query("SELECT name,email,date,bio,pol,hands FROM application_2 WHERE id=$uid");
foreach($res as $ell){
  $values['name']=strip_tags($ell['name']);
  $values['email']=strip_tags($ell['email']);
  $values['date']=strip_tags($ell['date']);
  $values['bio']=strip_tags($ell['bio']);
  $values['pol']=strip_tags($ell['pol']);
  $values['hands']=strip_tags($ell['hands']);

}
$res=$db->query("SELECT nomer FROM ultimate WHERE user_id=$uid");
$tar=array();
foreach($res as $ell){
  $tar[]=(int)strip_tags($ell['nomer']);
}
$t=implode('',$tar);
$values['ultimate']=$t;
printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
}
  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  // Проверяем ошибки.
  $errors = FALSE;
  if (empty($_POST['name']) || preg_match('/[^(\x7F-\xFF)|(\s)]/', $_POST['name'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  }

  if (empty($_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }

  if (empty($_POST['date'])) {
    setcookie('date_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('date_value', $_POST['date'], time() + 30 * 24 * 60 * 60);
  }

  if (empty($_POST['ultimate'])) {
    setcookie('ultimate_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    $t=implode('',$_POST['ultimate']);
    setcookie('ultimate_value', $t, time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['bio'])) {
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }

  if (empty($_POST['pol'])) {
    setcookie('pol_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('pol_value', $_POST['pol'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['hands'])) {
    setcookie('hands_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('hands_value', $_POST['hands'], time() + 30 * 24 * 60 * 60);
  }

// *************
// TODO: тут необходимо проверить правильность заполнения всех остальных полей.
// Сохранить в Cookie признаки ошибок и значения полей.
// *************

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
    setcookie('email_error', '', 100000);
    setcookie('date_error', '', 100000);
    setcookie('ultimate_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('pol_error', '', 100000);
    setcookie('hands_error', '', 100000);
  }
  $user = 'u47744';
$pass = '9352325';
$db = new PDO('mysql:host=localhost;dbname=u47744', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
        $uid=$_SESSION['uid'];
        $stmt=$db->prepare("UPDATE application_2 SET name=?,email=?,date=?,bio=?,pol=?,hands=? WHERE id=$uid");
        $stmt->execute([$_POST['name'],$_POST['email'],$_POST['date'],$_POST['bio'],$_POST['pol'],$_POST['hands']]);
        $db->query("DELETE FROM ultimate WHERE user_id=$uid");
        $stmt=$db->prepare("INSERT INTO ultimate SET user_id=?,nomer=?");
        foreach($_POST['ultimate'] as $ell){
          $stmt->execute([$uid,$ell]);
        }
    // TODO: перезаписать данные в БД новыми данными,
    // кроме логина и пароля.
  }
  else {
    // Генерируем уникальный логин и пароль.
    // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
    $login = substr(uniqid(time()), 1,8);
    $pass = substr( md5($_POST['email']),5,8);
    // Сохраняем в Cookies.
    setcookie('login', $login);
    setcookie('pass', $pass);
    
$Tarelki= implode(',',$_POST['ultimate']);
// Подготовленный запрос. Не именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO application_2 SET name = ?, email = ?, data = ?, bio = ?, pol = ?, hands = ?");
  $stmt -> execute([$_POST['name'],$_POST['email'],$_POST['date'],$_POST['bio'],$_POST['pol'],$_POST['hands']]);
  $stmt = $db->prepare("INSERT INTO ultimate SET user_id = ?, nomer = ?");
  $id = $db->lastInsertId();
  foreach ($_POST['ultimate'] as $per) { 
    $stmt-> execute([$id,$per]);
  } 
  $stmt=$db->prepare("INSERT INTO loginparol SET id=?,login=?,parol=?");
  $stmt->execute([$id,$login,md5($pass)]);
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

    // TODO: Сохранение данных формы, логина и хеш md5() пароля в базу данных.
    // ...
  }
 

  // Сохранение в XML-документ.
  // ...

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
