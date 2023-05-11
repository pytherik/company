<?php
include 'config.php';

//info lädt Klassen, die benötigt werden automatisch aus dem Ordner classes nach
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

$EmployeeClass = (PERSISTENCY === 'file') ? 'EmployeeFile' : 'EmployeeDb';
$DepartmentClass = (PERSISTENCY === 'file') ? 'DepartmentFile' : 'DepartmentDb';

$action = $_REQUEST['action'] ?? 'showList';
$area = $_REQUEST['area'] ?? 'employee';
$id = $_REQUEST['id'] ?? '';

// Variablenübergabe
$departmentName = $_POST['departmentName'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$departmentId = $_POST['departmentId'] ?? '';

try {

  switch ($action) {
    case 'showList':
      if ($area === 'employee') {
          $employees = (new $EmployeeClass())->getAllAsObjects();
      } else if ($area === 'department') {
          $departments = (new $DepartmentClass())->getAllAsObjects();
      }
      $view = $action;
      break;
    case 'showAllEmployees':
      $department = (new $DepartmentClass())->getObjectById($id);
      $employees = (new $EmployeeClass())->getAllEmployeesByDepartment($department);
      $view = 'showList';
      break;
    case 'showUpdate':
      if ($area === 'employee') {
          $employee = (new $EmployeeClass())->getObjectById($id);
          $departments = (new $DepartmentClass())->getAllAsObjects();
      } else if ($area === 'department') {
          $department = (new $DepartmentClass())->getObjectById($id);
      }
      $activity = 'bearbeiten';
      $view = 'showUpdateAndCreate';
      break;
    case 'showCreate':
      if ($area === 'employee') {
          $departments = (new $DepartmentClass())->getAllAsObjects();
      }
      $activity = 'erstellen';
      $view = 'showUpdateAndCreate';
      break;
    case 'delete':
      if ($area === 'employee') {
          (new $EmployeeClass())->delete($id);
          $employees = (new $EmployeeClass())->getAllAsObjects();
      } else if ($area === 'department') {
          (new $DepartmentClass())->delete($id);
          $departments = (new $DepartmentClass())->getAllAsObjects();
        }
      $view = 'showList';
      break;
    case 'update':
      if ($area === 'employee') {
          $employee = new $EmployeeClass($id, $firstName, $lastName, $departmentId);
          $employee->updateObject();
          $employees = (new $EmployeeClass())->getAllAsObjects();
      } else if ($area === 'department') {
          $department = new $DepartmentClass($id, $departmentName);
          $department->updateObject();
          $departments = (new $DepartmentClass())->getAllAsObjects();
      }
      $view = 'showList';
      break;
    case 'create':
      if ($area === 'employee') {
          (new $EmployeeClass())->createNewObject($firstName, $lastName, $departmentId);
      } else if ($area === 'department') {
          (new $DepartmentClass())->createNewObject($departmentName);
      }
    default:
      // falls unerwarteter Wert für $action übergeben wird
      if ($area === 'employee') {
          $employees = (new $EmployeeClass())->getAllAsObjects();
      } else if ($area === 'department') {
          $departments = (new $DepartmentClass())->getAllAsObjects();
      }
      $view = 'showList';
      break;
  }
} catch (Exception $e) {
  $view = 'error';
  $area = '';
}

include sprintf("views/%s.php", $view . ucfirst($area));
