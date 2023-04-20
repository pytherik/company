<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no,
        initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/style.css">
  <title><?= ucfirst($activity) ?></title>
</head>
<body>
<div class="wrapper">
  <h1>Mitarbeiter <?= $activity ?></h1>
  <a href="index.php?action=showList">
    <button class="btn">Abbruch</button>
  </a>
  <form action="index.php" method="post">
    <div class="table">
      <div class="row green">
        <!--        <div class="cell">ID</div>-->
        <div class="cell">Vorname</div>
        <div class="cell">Nachname</div>
        <div class="cell">Abteilung ID</div>
        <div class="cell">save</div>
        <div class="cell">reset</div>
      </div>
      <div class="row">

        <!--  hidden fields zur stillen übergabe der jeweiligen Parameter -->
        <input type="hidden" name="action"
               value="<?php echo (isset($employee)) ? 'update' : 'create' ?>">
        <!--        id Übergabe nur bei update-->
        <?php if (isset($employee)) { ?>
          <input type="hidden" name="id"
                 value="<?= $employee->getId() ?>">
        <?php } ?>

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
          <input class="save" type="submit" value=&#10004;>
        </div>
        <div>&nbsp;&nbsp;
          <input class="reset" type="reset" value=&olarr;>
        </div>
      </div>
    </div>
  </form>
  <div class="warning">
    <span class="message"></span>
  </div>
</body>
</html>