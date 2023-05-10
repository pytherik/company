<?php

class EmployeeDb implements Saveable
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
      $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
      $sql = 'SELECT * from employee';
      $result = $dbh->query($sql);
      $employees = [];
      while ($row = $result->fetchObject(__CLASS__)) {
        $employees[] = $row;
      }
      $dbh = null;
    } catch (PDOException $e) {
      throw new Exception($e->getMessage() . ' ' . implode('-', $e->getTrace()) . ' ' . $e->getCode() . ' ' . $e->getLine());
    }
    return $employees;
  }

  /**
   * @param int $id
   * @return EmployeeDb|false
   * @throws Exception
   */
  public function getObjectById(int $id): EmployeeDb|false
  {
    try {
      $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
      //info Version bisher
      /*
       * $sql = "SELECT * from employee WHERE id = :id";
       * $result = $dbh->query($sql);
       * $employee = $result-fetch_object(__CLASS__);
      */
      //info Version mit prepared Statement

      //info nur variable Werte werden mit :... gekennzeichnet
      $sql = "SELECT * from employee WHERE id = :id";

      //info sql wird an die SQL-Datenbank geschickt und es wird eine Syntaxprüfung durchgeführt
      $stmt = $dbh->prepare($sql);

      //info die Werte für die Platzhalter :... werden auf Datentyp überprüft und dann in das sql-Statement eingesetzt
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      //info das vollständige Statement wird in der Datenbank ausgeführt
      $stmt->execute();

      //info die zurückgegebenen Daten werden ausgelesen
      $employee = $stmt->fetchObject(__CLASS__);

      $dbh = null;
    } catch (PDOException $e) {
      throw new Exception($e->getMessage() . ' ' . implode('-', $e->getTrace()) . ' ' . $e->getCode() . ' ' . $e->getLine());
    }
    return $employee;
  }

  /**
   * @param string $firstName
   * @param string $lastName
   * @param int $departmentId
   * @return EmployeeDb
   * @throws Exception
   */
    public function createNewObject(string $firstName, string $lastName, int $departmentId): EmployeeDb
  {
    try {
      $sql = "INSERT INTO employee (id, firstName, lastName, departmentId) VALUES (NULL, :firstName, :lastName, :departmentId)";
      $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam('firstName', $firstName, PDO::PARAM_STR);
      $stmt->bindParam('lastName', $lastName, PDO::PARAM_STR);
      $stmt->bindParam('departmentId', $departmentId, PDO::PARAM_INT);
      $stmt->execute();
      $id = $dbh->lastInsertId();
      $dbh = null;
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
    return new EmployeeDb($id, $firstName, $lastName, $departmentId);
  }

  /**
   * @return void
   * @throws Exception
   */
  public function updateObject(): void
  {
    try {
      $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
      $sql = "UPDATE employee SET 
                    firstName = :firstName,
                    lastName = :lastName, 
                    departmentId = :departmentId 
                WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam('firstName', $this->firstName, PDO::PARAM_STR);
      $stmt->bindParam('lastName', $this->lastName, PDO::PARAM_STR);
      $stmt->bindParam('departmentId', $this->departmentId, PDO::PARAM_INT);
      $stmt->bindParam('id', $this->id, PDO::PARAM_INT);
      $stmt->execute();
      $dbh = null;
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }

  /**
   * @param int $id
   * @return void
   * @throws Exception
   */
  public function delete(int $id): void
  {
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
    return ((new Department())->getObjectById($this->departmentId))->getName();
  }
}