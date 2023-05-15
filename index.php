<?php
include 'config.php';

//info lädt Klassen, die benötigt werden automatisch nach
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

//info Auslagern der PERSISTENCY Fallunterscheidung aus switch case
$EmployeeClass = (PERSISTENCY === 'file') ? 'EmployeeFile' : 'EmployeeDb';
$DepartmentClass = (PERSISTENCY === 'file') ? 'DepartmentFile' : 'DepartmentDb';

//info Klasse View übernimmt heading, title und Navigationsinformationen
$view = new View();

$action = $_REQUEST['action'] ?? 'showList';
$area = $_REQUEST['area'] ?? 'employee';
$id = $_REQUEST['id'] ?? '';

//info Variablenübergabe
$departmentName = $_POST['departmentName'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$departmentId = $_POST['departmentId'] ?? '';

try {

  switch ($action) {
    case 'showList':
      if ($area === 'employee') {
          $employees = (new $EmployeeClass())->getAllAsObjects();
          $view->setHeading('Alle Mitarbeiter');
      } else if ($area === 'department') {
          $departments = (new $DepartmentClass())->getAllAsObjects();
          $view->setHeading('Alle Abteilungen');
      }
      $view->setNavigation($action);
      break;
    case 'showAllEmployees':
      $department = (new $DepartmentClass())->getObjectById($id);
      $employees = $department->getEmployees();
      $view->setNavigation('showList');
      $abteilung = $department->getName();
      $view->setHeading("Mitarbeiter $abteilung");
      break;
    case 'showUpdate':
      if ($area === 'employee') {
          $employee = (new $EmployeeClass())->getObjectById($id);
          $departments = (new $DepartmentClass())->getAllAsObjects();
          $view->setHeading('Mitarbeiter bearbeiten');
      } else if ($area === 'department') {
          $department = (new $DepartmentClass())->getObjectById($id);
          $view->setHeading('Abteilung bearbeiten');
      }
      $view->setNavigation('showUpdateAndCreate');
      break;
    case 'showCreate':
      if ($area === 'employee') {
          $departments = (new $DepartmentClass())->getAllAsObjects();
          $view->setHeading('Mitarbeiter erstellen');
      } else {
        $view->setHeading('Neue Abteilung');
      }
      $view->setNavigation('showUpdateAndCreate');
      break;
    case 'delete':
      if ($area === 'employee') {
          (new $EmployeeClass())->delete($id);
          $employees = (new $EmployeeClass())->getAllAsObjects();
          $view->setHeading('Alle Mitarbeiter');
      } else if ($area === 'department') {
          (new $DepartmentClass())->delete($id);
          $departments = (new $DepartmentClass())->getAllAsObjects();
          $view->setHeading('Alle Abteilungen');
        }
      $view->setNavigation('showList');
      break;
    case 'update':
      if ($area === 'employee') {
          $employee = new $EmployeeClass($id, $firstName, $lastName, $departmentId);
          $employee->updateObject();
          $employees = (new $EmployeeClass())->getAllAsObjects();
          $view->setHeading('Alle Mitarbeiter');
      } else if ($area === 'department') {
          $department = new $DepartmentClass($id, $departmentName);
          $department->updateObject();
          $departments = (new $DepartmentClass())->getAllAsObjects();
          $view->setHeading('Alle Abteilungen');
      }
      $view->setNavigation('showList');
      break;
    case 'create':
      if ($area === 'employee') {
          (new $EmployeeClass())->createNewObject($firstName, $lastName, $departmentId);
          $view->setHeading('Alle Mitarbeiter');
      } else if ($area === 'department') {
          (new $DepartmentClass())->createNewObject($departmentName);
          $view->setHeading('Alle Abteilungen');
      }
    default:
      // falls unerwarteter Wert für $action übergeben wird
      if ($area === 'employee') {
          $employees = (new $EmployeeClass())->getAllAsObjects();
          $view->setHeading('Alle Mitarbeiter');
      } else if ($area === 'department') {
          $departments = (new $DepartmentClass())->getAllAsObjects();
          $view->setHeading('Alle Abteilungen');
      }
      $view->setNavigation('showList');
      break;
  }
} catch (Exception $e) {
  $view->setNavigation('error');
  $view->setHeading('WTF 😨 ?!');
  $area = '';
}

include sprintf("views/%s.php", $view->getAction() . ucfirst($area));
