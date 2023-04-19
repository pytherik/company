
<?php
// erstes Ziel: list.php anzeigen lassen
include 'classes/Employee.php';
$dummy = new Employee();
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
// isset($_GET['action']) ? $view = $_GET['action'] : $view = 'showList';

// es geht noch kürzer - 'null coalescing' operator
// Koaleszenz: das Zusammenwachsen oder Verschmelzen von
//             getrennt wahrnehmbaren Dingen oder Teilen

$action = $_GET['action'] ?? 'showList';
$id = $_GET['id'] ?? '';

switch ($action) {
  case 'showList':
    $emps = $dummy->getSeedEmployees();
    $view = 'showList';
    break;
  case 'showCreate':
    $activity = 'erstellen';
    $view = 'showUpdate'; // eine Seite für beides: create und update
    break;
  case 'showUpdate':
    $employee = $dummy->getEmployeeById($id);
    $activity = 'bearbeiten';
    $view = 'showUpdate';
    break;
}

include sprintf("views/%s.php", $view);