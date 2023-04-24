<?php
// erstes Ziel: list.php anzeigen lassen
include 'config.php';
include 'classes/ConnectDB.php';
include 'classes/Employee.php';
include 'classes/Department.php';
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
// isset($_GET['action']) ? $view = $_GET['action'] : $view = 'showList';
//
// es geht noch kürzer - 'null coalescing' operator
// Koaleszenz: das Zusammenwachsen oder Verschmelzen von
//             getrennt wahrnehmbaren Dingen oder Teilen

$action = $_REQUEST['action'] ?? 'showList';
$area = $_REQUEST['area'] ?? 'employee';
$id = $_REQUEST['id'] ?? '';

// Variablenübergabe
$departmentName = $_POST['departmentName'];
$firstName = $_POST['firstName'] ?? '';
$lasstName = $_POST['lastName'] ?? '';
$departmentId = $_POST['departmentId'] ?? '';

// Übergabevariablen desinfizieren (sanitize)
// kleiner Ausflug XSS: in input-text-Felder javascript schreiben,
// z.B. <script>alert('mähh');</script>

try {

  switch ($action) {
    case 'showList':
      if ($area === 'employee') {
        $employees = (new Employee())->getAllAsObjects();
      } else if ($area === 'department') {
        $departments = (new Department())->getAllAsObjects();
//        $departments = (new Department())->getFromDatabase();
      }
      $view = $action;
      break;
    case 'showUpdate':
      if ($area === 'employee') {
        $employee = (new Employee())->getEmployeeById($id);
        $activity = 'bearbeiten';
      } else if ($area === 'department'){
        $department = (new Department())->getDepartmentById($id);
        $activity = 'bearbeiten';
      }
      $view = 'showUpdateAndCreate';
      break;
    case 'showCreate':
      if ($area === 'employee') {
        $activity = 'erstellen';
      } else if ($area === 'department') {
        $activity = 'erstellen';
      }
      $view = 'showUpdateAndCreate';
      break;
    case 'delete':
      if ($area === 'employee') {
        (new Employee())->delete($id);
        $employees = (new Employee())->getAllAsObjects();
      } else if ($area === 'department') {
        (new Department())->delete($id);
        $departments = (new Department())->getAllAsObjects();
      }
      $view = 'showList';
      break;
    case 'update':
      if ($area === 'employee') {
        $employee = new Employee($id, $firstName, $lasstName, $departmentId);
        $employee->store();
        $employees = (new Employee())->getAllAsObjects();
      }else if ($area === 'department') {
        $department = new Department($id, $departmentName);
        $department->store();
        $departments = (new Employee())->getAllAsObjects();
      }
      $view = 'showList';
      break;
    case 'create':
      if ($area === 'employee') {
        (new Employee())->createNewEmployee($firstName, $lasstName, $departmentId);
      } else if ($area === 'department') {
        (new Department())->createNewDepartment($departmentName);
      }
    default:
      // falls unerwarteter Wert für $action übergeben wird
      if($area === 'employees') {
      $employees = (new Employee())->getAllAsObjects();
      } else if ($area === 'department'){
      $departments = (new Department())->getAllAsObjects();
      }
      $view = 'showList';
      break;
  }
} catch (Exception $e) {
  $view = 'error';
}

include sprintf("views/%s.php", $view . ucfirst($area));


//echo "<pre>";
//print_r($_REQUEST);
//echo "</pre>";