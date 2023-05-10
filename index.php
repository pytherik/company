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
        if (PERSISTENCY === 'file') {
          $departments = (new DepartmentFile())->getAllAsObjects();
        } else {
          $departments = (new DepartmentDb())->getAllAsObjects();
        }
//        $departments = (new DepartmentFile())->getFromDatabase();
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
        if (PERSISTENCY === 'file') {
          $departments = (new DepartmentFile())->getAllAsObjects();
        } else {
          $departments = (new DepartmentDb())->getAllAsObjects();
        }
      } else if ($area === 'department') {
        if (PERSISTENCY === 'file') {
          $department = (new DepartmentFile())->getObjectById($id);
        } else {
          $department = (new DepartmentDb())->getObjectById($id);
        }
      }
      $activity = 'bearbeiten';
      $view = 'showUpdateAndCreate';
      break;
    case 'showCreate':
      if ($area === 'employee') {
        if (PERSISTENCY === 'file') {
          $departments = (new DepartmentFile())->getAllAsObjects();
        } else {
          $departments = (new DepartmentDb())->getAllAsObjects();
        }
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
        if (PERSISTENCY === 'file') {
          (new DepartmentFile())->delete($id);
          $departments = (new DepartmentFile())->getAllAsObjects();
        } else {
          (new DepartmentDb())->delete($id);
          $departments = (new DepartmentDb())->getAllAsObjects();
        }
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
        if (PERSISTENCY === 'file') {
          $department = new DepartmentFile($id, $departmentName);
          $department->updateObject();
          $departments = (new DepartmentFile())->getAllAsObjects();
        } else {
          $department = new DepartmentDb($id, $departmentName);
          $department->updateObject();
          $departments = (new DepartmentDb())->getAllAsObjects();
        }
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
        if (PERSISTENCY === 'file') {
          (new DepartmentFile())->createNewObject($departmentName);
        } else {
          (new DepartmentDb())->createNewObject($departmentName);
        }
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
        if (PERSISTENCY === 'file') {
          $departments = (new DepartmentFile())->getAllAsObjects();
        } else {
          $departments = (new DepartmentDb())->getAllAsObjects();
        }
      }
      $view = 'showList';
      break;
  }
} catch (Exception $e) {
  $view = 'error';
  $area = '';
}

include sprintf("views/%s.php", $view . ucfirst($area));
