<?php

class Employee
{
  private int $id;
  private string $firstname;
  private string $lastname;
  private int $departmentId;

  /**
   * @param int|null $id
   * @param string|null $firstname
   * @param string|null $lastname
   * @param int|null $departmentId
   */
  public function __construct(?int    $id = null, ?string $firstname = null,
                              ?string $lastname = null, ?int $departmentId = null)
  {
    if (isset($id) && isset($firstname) && isset($lastname) && isset($departmentId)) {
      $this->id = $id;
      $this->firstname = $firstname;
      $this->lastname = $lastname;
      $this->departmentId = $departmentId;
    }
  }

  /**
   * @return Employee[]
   */
  public function getAllAsObjects(): array
  {
    $handle = fopen(CSV_PATH, 'r');
    $employees = [];
    while ($content = fgetcsv($handle)) {
      $employees[] = new Employee($content[0], $content[1], $content[2], $content[3]);
    }
    fclose($handle);
    return $employees;
  }

  public function getEmployeeById(int $id): Employee
  {
    $employee = new Employee();
    $employees = $this->getAllAsObjects();
    foreach ($employees as $e) {
      if ($id === $e->getId()) {
        $employee = $e;
      }
    }
    return $employee;
  }

  public function store():void
  {
    $employees = $this->getAllAsObjects();
    foreach ($employees as $key => $employee) {
      if ($employee->getId() === $this->getId()) {
        $employees[$key] = $this;
        break;
      }
    }
    unlink(CSV_PATH);
    $handle = fopen(CSV_PATH, 'w', FILE_APPEND);
    foreach ($employees as $employee) {
      $empNumArr = array_values((array)$employee);
      fputcsv($handle, $empNumArr, ',');
    }
    fclose($handle);
  }

  public function delete(int $id): void
  {
    $employees = $this->getAllAsObjects();
    foreach ($employees as $key => $employee) {
      if ($employee->getId() === $id) {
        unset($employees[$key]);
//        break;
      }
    }
    unlink(CSV_PATH);
    $handle = fopen(CSV_PATH, 'w', FILE_APPEND);
    foreach ($employees as $employee) {
      $empNumArr = array_values((array)$employee);
      fputcsv($handle, $empNumArr, ',');
    }
    fclose($handle);
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
  public function getFirstname(): string
  {
    return $this->firstname;
  }

  /**
   * @return string
   */
  public function getLastname(): string
  {
    return $this->lastname;
  }

  /**
   * @return int
   */
  public function getDepartmentId(): int
  {
    return $this->departmentId;
  }

}