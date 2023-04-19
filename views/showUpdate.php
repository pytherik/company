<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no,
        initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/style.css">
  <title>Document</title>
</head>
<body>
<div class="wrapper">
  <h1>Mitarbeiter <?= $activity ?></h1>
  <a href="index.php">
    <button class="btn">Abbruch</button>
  </a>
  <form action="" method="post">
    <div class="table">
      <div class="row green">
<!--        <div class="cell">ID</div>-->
        <div class="cell">Vorname</div>
        <div class="cell">Nachname</div>
        <div class="cell">Abteilung ID</div>
        <div class="cell">Speichern</div>
      </div>
      <div class="row">
<!--        <div class="cell">-->
          <input type="hidden" name="id"
                 value="<?php if (isset($employee)) echo $employee->getId() ?>">
<!--        </div>-->
        <div class="cell">
          <input type="text" name="firstName"
                 value="<?php if (isset($employee)) echo $employee->getFirstname() ?>"
                 size="18" autocomplete="off" autofocus required>
        </div>
        <div class="cell">
          <input type="text" name="lastName"
                 value="<?php if (isset($employee)) echo $employee->getLastname() ?>"
                 size="18" autocomplete="off" required>
        </div>
        <div class="cell">
          <input type="number" name="departmentId"
                 value="<?php if (isset($employee)) echo $employee->getDepartmentId() ?>"
                 min="1" max="100" autocomplete="off" required>
        </div>
        <div class="cell">
          <input class="save" type="submit" name="save" value=&#10004;>
        </div>
      </div>
    </div>
    <div class="warning">
      <span class="message"></span>
    </div>
  </form>
</body>
</html>