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
   * @return Employee2[]
   */
  public function getSeedEmployees(): array
  {
    return [
      new Employee(1, 'Schnuffi', 'Schneuz', 1),
      new Employee(2, 'Bibo', 'Bird', 4),
      new Employee(3, 'Hansi', 'Pample', 3),
      new Employee(4, 'Kalli', 'Kanone', 3),
    ];
  }

  public function getEmployeeById(int $id): Employee
  {
    $employee = new Employee();
    $employees = $this->getSeedEmployees();
    foreach($employees as $e) {
      if($id === $e->getId()){
        $employee = $e;
      }
    }
    return $employee;
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