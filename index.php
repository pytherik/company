<?php
include 'config.php';
include 'classes/Saveable.php';
include 'classes/Employee.php';
include 'classes/Department.php';
include 'classes/HtmlHelper.php';

$action = $_REQUEST['action'] ?? 'showList';
$area = $_REQUEST['area'] ?? 'employee';
$id = $_REQUEST['id'] ?? '';

// Variablenübergabe
$departmentName = $_POST['departmentName'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$lasstName = $_POST['lastName'] ?? '';
$departmentId = $_POST['departmentId'] ?? '';

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
        $employee = (new Employee())->getObjectById($id);
        $departments = (new Department())->getAllAsObjects();
      } else if ($area === 'department'){
        $department = (new Department())->getObjectById($id);
      }
      $activity = 'bearbeiten';
      $view = 'showUpdateAndCreate';
      break;
    case 'showCreate':
      if ($area === 'employee') {
        $departments = (new Department())->getAllAsObjects();
      }
      $activity = 'erstellen';
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
        $employee->updateObject();
        $employees = (new Employee())->getAllAsObjects();
      } else if ($area === 'department') {
        $department = new Department($id, $departmentName);
        $department->updateObject();
        $departments = (new Department())->getAllAsObjects();
      }
      $view = 'showList';
      break;
    case 'create':
      if ($area === 'employee') {
        (new Employee())->createNewObject($firstName, $lasstName, $departmentId);
      } else if ($area === 'department') {
        (new Department())->createNewObject($departmentName);
      }
    default:
      // falls unerwarteter Wert für $action übergeben wird
      if($area === 'employee') {
      $employees = (new Employee())->getAllAsObjects();
      } else if ($area === 'department'){
      $departments = (new Department())->getAllAsObjects();
      }
      $view = 'showList';
      break;
  }
} catch (Exception $e) {
  $view = 'error';
  $area = '';
}

include sprintf("views/%s.php", $view . ucfirst($area));

//echo "<pre>";
//print_r($_REQUEST);
//echo "</pre>";