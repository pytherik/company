<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no,
        initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/style.css">
  <title>Alle Mitarbeiter</title>
</head>
<body>
<div class="wrapper">
  <h1>Alle Mitarbeiter</h1>
  <?php include 'views/navigation.php' ?>
  <div class="table">
    <div class="row header">
      <!--        <div class="cell">ID</div>-->
      <div class="cell">Vorname</div>
      <div class="cell">Nachname</div>
      <div class="cell">Abteilung</div>
      <div class="cell">Löschen</div>
      <div class="cell">Ändern</div>
    </div>
    <?php
    foreach ($employees as $emp) {
      ?>
      <div class="row">
        <!--          <div class="cell" data-title="id">--><?php //echo $emp->getId() ?><!--</div>-->
        <div class="cell" data-title="vorname"><?php echo $emp->getFirstname() ?></div>
        <div class="cell" data-title="nachname"><?php echo $emp->getLastname() ?></div>
        <div class="cell" data-title="abteilungId"><?php echo $emp->getDepartmentId() ?></div>
        <div class="cell" data-title="löschen">
          <a href="index.php?id=<?php echo $emp->getId() ?>&action=delete&area=employee">
            <button class="delete">&#10006;</button>
          </a>
        </div>
        <div class="cell" data-title="ändern">
          <a href="index.php?id=<?php echo $emp->getId() ?>&action=showUpdate&area=employee">
            <button>&#10000;</button>
          </a>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</body>
</html>
