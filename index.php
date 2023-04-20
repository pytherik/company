<?php
// erstes Ziel: list.php anzeigen lassen
include 'config.php';
include 'classes/Employee.php';
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
// isset($_GET['action']) ? $view = $_GET['action'] : $view = 'showList';
//
// es geht noch kürzer - 'null coalescing' operator
// Koaleszenz: das Zusammenwachsen oder Verschmelzen von
//             getrennt wahrnehmbaren Dingen oder Teilen

$action = $_REQUEST['action'] ?? 'showList';
$id = $_REQUEST['id'] ?? '';

// Variablenübergabe
$firstName = $_POST['firstName'] ?? '';
$lasstName = $_POST['lastName'] ?? '';
$departmentId = $_POST['departmentId'] ?? '';

// Übergabevariablen desinfizieren (sanitize)
// kleiner Ausflug XSS: in input-text-Felder javascript schreiben,
// z.B. <script>alert('mähh');</script>

try {

  switch ($action) {
    case 'showList':
      $employees = (new Employee())->getAllAsObjects();
      $view = 'showList';
      break;
    case 'showUpdate':
      $employee = (new Employee())->getEmployeeById($id);
      $activity = 'bearbeiten';
      $view = 'showUpdateAndCreate';
      break;
    case 'showCreate':
      $activity = 'erstellen';
      $view = 'showUpdateAndCreate';
      break;
    case 'delete':
      (new Employee())->delete($id);
      $employees = (new Employee())->getAllAsObjects();
      $view = 'showList';
      break;
    case 'update':
      $employee = new Employee($id, $firstName, $lasstName, $departmentId);
      $employee->store();
      $employees = (new Employee())->getAllAsObjects();
      $view = 'showList';
      break;
    case 'create':
      (new Employee())->createNewEmployee($firstName, $lasstName, $departmentId);
    default:
      // falls unerwarteter Wert für $action übergeben wird
      $employees = (new Employee())->getAllAsObjects();
      $view = 'showList';
      break;
  }
} catch (Exception $e) {
  $view = 'error';
}

include sprintf("views/%s.php", $view);


//echo "<pre>";
//print_r($_REQUEST);
//echo "</pre>";