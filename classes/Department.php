<?php

class Department #extends ConnectDB
{
  private int $id;
  private string $name;

  /**
   * @param int|null $id
   * @param string|null $name
   */
  public function __construct(?int $id = null, ?string $name = null)
  {
    if (isset($id) && isset($name)) {
      {
        $this->id = $id;
        $this->name = $name;
      }
    }
  }

  /**
   * @param int $id
   * @return Department
   */
  public function getDepartmentById(int $id): Department
  {
    $department = new Department();
    $departments = $this->getAllAsObjects();
    foreach ($departments as $e) {
      if ($id === $e->getId()) {
        $department = $e;
      }
    }
    return $department;
  }


//  public function getFromDatabase(): array
//  {
//    try {
//      $pdo = $this->connect();
//      $sql = "SELECT * FROM department";
//      $stmt = $pdo->query($sql);
//      $departments = [];
//      while($dep = $stmt->fetchObject(__CLASS__)){
//        $departments[] = $dep;
//      }
//    } catch (PDOException $e) {
//      throw new PDOException('Datenbank sagt nein: ' . $e->getMessage());
//    }
//    return $departments;
//  }

  /**
   * @return array
   */
  public function getAllAsObjects(): array
  {
    try {

      if (!is_file(CSV_PATH_DEPARTMENT)) {
        fopen(CSV_PATH_DEPARTMENT, 'w');
      }

      $handle = fopen(CSV_PATH_DEPARTMENT, 'r');
      $departments = [];
      while ($content = fgetcsv($handle)) {
        $departments[] = new Department((int)$content[0], $content[1]);
      }
      fclose($handle);
    } catch (Error $e) {
      // wird im view error.php ausgegeben
      throw new Error('Etwas ist nicht ok: '. $e->getMessage() . ' ' . implode('-', $e->getTrace()) . ' ' . $e->getCode() . ' ' . $e->getLine());
    }
    return $departments;
  }

  /**
   * @param string $name
   * @return Department
   */
  public function createNewDepartment(string $name): Department
  {
    if (!is_file(CSV_PATH_DEPARTMENT_ID_COUNTER)) {
      file_put_contents(CSV_PATH_DEPARTMENT_ID_COUNTER, 1);
    }
    $id = file_get_contents(CSV_PATH_DEPARTMENT_ID_COUNTER);

    $department = new Department($id, $name);
    $departments = $this->getAllAsObjects();
    $departments[] = $department;
    $this->storeInFile($departments);

    file_put_contents(CSV_PATH_DEPARTMENT_ID_COUNTER, $id + 1);
    return $department;
  }

  /**
   * @param int $id
   * @return void
   */
  public function delete(int $id): void
  {
    $departments = $this->getAllAsObjects();
    foreach ($departments as $key => $department) {
      if ($department->getId() === $id) {
        unset($departments[$key]);
        break;
      }
    }
    $this->storeInFile($departments);
  }

  /**
   * @return void
   */
  public function store(): void
  {
    try {
      $departments = $this->getAllAsObjects();
      foreach ($departments as $key => $department) {
        if ($department->getId() === $this->getId()) {
          $departments[$key] = $this;
          break;
        }
      }
      $this->storeInFile($departments);
    } catch (Error $e) {
      throw new Error('Fehler in store(): ' . $e->getMessage());
    }
  }

  /**
   * @param array $departments
   * @return void
   */
  private function storeInFile(array $departments): void
  {
    try {
      unlink(CSV_PATH_DEPARTMENT);
      $handle = fopen(CSV_PATH_DEPARTMENT, 'w', FILE_APPEND);
      foreach ($departments as $department) {
        $depNumArr = array_values((array)$department);
        fputcsv($handle, $depNumArr, ',');
      }
      fclose($handle);
    } catch (Error $e) {
      throw new Error('Fehler in store in file: ' . $e->getMessage() . ' ' . implode('-', $e->getTrace()));
    }
  }

  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

}