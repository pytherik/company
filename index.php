<?php
include 'config.php';

//info lädt Klassen, die benötigt werden automatisch aus dem Ordner classes nach
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

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
        if (PERSISTENCY === 'file') {
          $employees = (new EmployeeFile())->getAllAsObjects();
        } else {
          $employees = (new EmployeeDb())->getAllAsObjects();
        }
      } else if ($area === 'department') {
        $departments = (new Department())->getAllAsObjects();
//        $departments = (new Department())->getFromDatabase();
      }
      $view = $action;
      break;
    case 'showUpdate':
      if ($area === 'employee') {
        if (PERSISTENCY === 'file') {
          $employee = (new EmployeeFile())->getObjectById($id);
        } else {
          $employee = (new EmployeeDb())->getObjectById($id);
        }
        $departments = (new Department())->getAllAsObjects();
      } else if ($area === 'department') {
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
        if (PERSISTENCY === 'file') {
          (new EmployeeFile())->delete($id);
          $employees = (new EmployeeFile())->getAllAsObjects();
        } else {
          (new EmployeeDb())->delete($id);
          $employees = (new EmployeeDb())->getAllAsObjects();
        }
      } else if ($area === 'department') {
        (new Department())->delete($id);
        $departments = (new Department())->getAllAsObjects();
      }
      $view = 'showList';
      break;
    case 'update':
      if ($area === 'employee') {
        if (PERSISTENCY === 'file') {
          $employee = new EmployeeFile($id, $firstName, $lastName, $departmentId);
          $employee->updateObject();
          $employees = (new EmployeeFile())->getAllAsObjects();
        } else {
          $employee = new EmployeeDb($id, $firstName, $lastName, $departmentId);
          $employee->updateObject();
          $employees = (new EmployeeDb())->getAllAsObjects();
        }
      } else if ($area === 'department') {
        $department = new Department($id, $departmentName);
        $department->updateObject();
        $departments = (new Department())->getAllAsObjects();
      }
      $view = 'showList';
      break;
    case 'create':
      if ($area === 'employee') {
        if (PERSISTENCY === 'file') {
          (new EmployeeFile())->createNewObject($firstName, $lastName, $departmentId);
        } else {
          (new EmployeeDb())->createNewObject($firstName, $lastName, $departmentId);
        }
      } else if ($area === 'department') {
        (new Department())->createNewObject($departmentName);
      }
    default:
      // falls unerwarteter Wert für $action übergeben wird
      if ($area === 'employee') {
        if (PERSISTENCY === 'file') {
          $employees = (new EmployeeFile())->getAllAsObjects();
        } else {
          $employees = (new EmployeeDb())->getAllAsObjects();
        }
      } else if ($area === 'department') {
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
