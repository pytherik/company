<?php

class EmployeeFile implements Saveable
{
  private int $id;
  private string $firstName;
  private string $lastName;
  private int $departmentId;

  /**
   * @param int|null $id
   * @param string|null $firstName
   * @param string|null $lastName
   * @param int|null $departmentId
   */
  public function __construct(?int    $id = null, ?string $firstName = null,
                              ?string $lastName = null, ?int $departmentId = null)
  {
    if (isset($id) && isset($firstName) && isset($lastName) && isset($departmentId)) {
      $this->id = $id;
      $this->firstName = $firstName;
      $this->lastName = $lastName;
      $this->departmentId = $departmentId;
    }
  }

  /**
   * @return array
   * @throws Exception
   */
  public function getAllAsObjects(): array
  {
      try {

        if (!is_file(CSV_PATH_EMPLOYEE)) {
          fopen(CSV_PATH_EMPLOYEE, 'w');
        }
        $handle = fopen(CSV_PATH_EMPLOYEE, 'r');
        $employees = [];
        while ($content = fgetcsv($handle)) {
          $employees[] = new EmployeeFile($content[0], $content[1], $content[2], $content[3]);
        }
        fclose($handle);
      } catch (Error $e) {
        //todo style error.php
        throw new Exception($e->getMessage() . ' ' . implode('-', $e->getTrace()) . ' ' . $e->getCode() . ' ' . $e->getLine());
      }
    return $employees;
  }

  /**
   * @param int $id
   * @return EmployeeFile|false
   * @throws Exception
   */
  public function getObjectById(int $id): EmployeeFile|false
  {
      $employee = new EmployeeFile();
      $employees = $this->getAllAsObjects();
      foreach ($employees as $e) {
        if ($id === $e->getId()) {
          $employee = $e;
        }
      }
      return $employee;
  }

  /**
   * @param string $firstName
   * @param string $lastName
   * @param int $departmentId
   * @return EmployeeFile
   * @throws Exception
   */
  public function createNewObject(string $firstName, string $lastName, int $departmentId): EmployeeFile
  {
      if (!is_file(CSV_PATH_EMPLOYEE_ID_COUNTER)) {
        file_put_contents(CSV_PATH_EMPLOYEE_ID_COUNTER, 1);
      }
      // nächste freie id auslesen
      $id = file_get_contents(CSV_PATH_EMPLOYEE_ID_COUNTER);

      $employee = new EmployeeFile($id, $firstName, $lastName, $departmentId);
      $employees = $this->getAllAsObjects();
      $employees[] = $employee; // neuen employee zu Liste ($employees) hinzufügen
      $this->storeInFile($employees);

      file_put_contents(CSV_PATH_EMPLOYEE_ID_COUNTER, $id + 1);
      return new EmployeeFile($id, $firstName, $lastName, $departmentId);
    }

  /**
   * @return void
   * @throws Exception
   */
  public function updateObject(): void
  {
      try {
        $employees = $this->getAllAsObjects();
        foreach ($employees as $key => $employee) {
          if ($employee->getId() === $this->getId()) {
            $employees[$key] = $this;
            break;
          }
        }
        $this->storeInFile($employees);
      } catch (Error $e) {
        throw new Exception('Fehler in store(): ' . $e->getMessage());
      }
  }

  /**
   * @param array $employees
   * @return void
   * @throws Exception
   */
  private function storeInFile(array $employees): void
  {
    try {
      unlink(CSV_PATH_EMPLOYEE);
      $handle = fopen(CSV_PATH_EMPLOYEE, 'w', FILE_APPEND);
      foreach ($employees as $employee) {
        $empNumArr = array_values((array)$employee);
        fputcsv($handle, $empNumArr, ',');
      }
      fclose($handle);
    } catch (Error $e) {
      throw new Exception('Fehler in store in file: ' . $e->getMessage() . ' ' . implode('-', $e->getTrace()));
    }
  }

  /**
   * @param int $id
   * @return void
   * @throws Exception
   */
  public function delete(int $id): void
  {
    if (PERSISTENCY === 'file') {
      $employees = $this->getAllAsObjects();
      foreach ($employees as $key => $employee) {
        if ($employee->getId() === $id) {
          unset($employees[$key]);
          break;
        }
      }
      $this->storeInFile($employees);
    } else {
      try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
        $sql = "DELETE FROM employee WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $dbh = null;
      } catch (PDOException $e) {
        throw new Exception($e->getMessage());
      }
    }
  }

  /**
   * @return int
   */
  public
  function getId(): int
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getFirstName(): string
  {
    return $this->firstName;
  }

  /**
   * @return string
   */
  public function getLastName(): string
  {
    return $this->lastName;
  }

  /**
   * @return int
   */
  public function getDepartmentId(): int
  {
    return $this->departmentId;
  }


  public function getDepartmentName(): string
  {
//    $departments = (new Department())->getAllAsObjects();
//    foreach ($departments as $department) {
//      if ($this->getDepartmentId() === $department->getId()) {
//        return $department->getName();
//      } else {
//        continue;
//      }
//    }
//    return 'NULL';
    return ((new Department())->getObjectById($this->departmentId))->getName();
  }
}