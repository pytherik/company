<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no,
        initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/style.css">
  <title><?= $view->getHeading() ?></title>
</head>
<body>
<div class="wrapper">
  <h1 class="heading"><?= $view->getHeading() ?></h1>
  <?php include 'views/navigation.php' ?>
  <form action="index.php" method="post">
    <div class="table">
      <div class="row green">
        <!--        <div class="cell">ID</div>-->
        <div class="cell">Abteilung</div>
        <div class="cell"></div>
        <div class="cell"></div>
        <div class="cell">save</div>
        <div class="cell">reset</div>
      </div>
      <div class="row">

        <!--  hidden fields zur stillen übergabe der jeweiligen Parameter -->
        <input type="hidden" name="area" value="department">
        <input type="hidden" name="action"
               value="<?php echo (isset($department)) ? 'update' : 'create' ?>">
        <!--        id Übergabe nur bei update-->
        <?php if (isset($department)) { ?>
          <input type="hidden" name="id"
                 value="<?= $department->getId() ?>">
        <?php } ?>

        <div class="cell">
          <input type="text" name="departmentName"
                 value="<?php if (isset($department)) echo $department->getName() ?>"
                 size="18" autocomplete="off" autofocus required>
        </div>
        <div class="cell"></div>
        <div class="cell"></div>
        <div class="cell">
          <input class="save center" type="submit" value=&#10004;>
        </div>
        <div>&nbsp;&nbsp;
          <input class="reset center" type="reset" value=&olarr;>
        </div>
      </div>
    </div>
  </form>
  <div class="warning">
    <span class="message"></span>
  </div>
</body>
</html>