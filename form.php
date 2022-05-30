<!DOCTYPE html>

<html lang="ru">

  <head>
    <title>Задание_3</title>
	<link rel="stylesheet" href="style.css">
	<meta charset="utf-8">
	<style>
    .error {
      border: 2px solid red;
    }
    </style>

  </head>

  <body style="margin:0;" bgcolor=#2e2525>
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

        <h2 class="h1" ;="" id="form">Форма</h2>
		<?php if(empty($_SESSION['login'])){
       ?>
       <a href="login.php">Войти</a>
       <?php
       } else {?><a href="login.php">Выйти</a><?php } ?>


   <form action="" method="POST">

              Имя:<br/>
              <input name="name" <?php if ($errors['name']) {print 'class="error"';} ?> value="<?php print $values['name']; ?> ">
			  <br/>
              E-mail:<br/>
              <input name="email"
                     type="email"  <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?> "><br />
					 <br/>
              Дата рождения:<br/>
              <input name="date"
                     type="date"  <?php if ($errors['date']) {print 'class="error"';} ?> value="<?php print $values['date']; ?> "><br />
          <br/>
          Пол:<br/>
              <input type="radio" checked="checked"
                     name="pol" value="Значение1" <?php if ($values['pol']=='Женский'){print 'checked';} ?>>
              Женский
              <input type="radio"
                     name="pol" value="Значение2" <?php if ($values['pol']=='Мужской'){print 'checked';} ?>>
              Мужской<br/>
          Количество конечностей:<br/>
              <input type="radio" checked="checked"
                     name="hands" value="Значение1" <?php if ($values['hands']=='2(или меньше)'){print 'checked';} ?>>
              2(или меньше)
              <input type="radio"
                     name="hands" value="Значение2" <?php if ($values['hands']=='3-15'){print 'checked';} ?>>
              3-15
              <input type="radio"
                     name="hands" value="Значение3" <?php if ($values['hands']=='16(или больше)'){print 'checked';} ?>>
              16(или больше)<br/>
              Сверхспособности:
              <br/>
              <select name="ultimate[]"
                      multiple="multiple">
				  <option value="1" <?php if ($errors['bio']) {print 'class="error"';} ?><?php
                  $mas=str_split($values['ultimate']);
                  foreach($mas as $per)
                  if($per==1) {print 'selected';}?>>Бессмертие</option>
				  <option value="2" <?php if ($errors['bio']) {print 'class="error"';} ?><?php
                  $mas=str_split($values['ultimate']);
                  foreach($mas as $per)
                  if($per==2) {print 'selected';}?>>Прохождение сквозь стены</option>
				  <option value="3" <?php if ($errors['bio']) {print 'class="error"';} ?><?php
                  $mas=str_split($values['ultimate']);
                  foreach($mas as $per)
                  if($per==3) {print 'selected';}?>>Левитация</option>
              </select><br/>
              Биография:<br/>
              <textarea name="bio" <?php if ($errors['bio']) {print 'class="error"';} ?>><?php print $values['bio']; ?></textarea><br/>
          <br/>
          <input type="submit" value="Отправить" />
    </form><br/>
	<footer>
      <div class="container"><h2>(c)Варламов Кирилл 27/1</h2></div>
    </footer>
	<div class="mallbery-caa" style="z-index: 2147483647 !important; text-transform: none !important; position: fixed;"></div>
</body>
<div id="sm-wrapper"></div>
</html>