<?php

class DepartmentDb extends Department
{
  /**
   * @param int $id
   * @return DepartmentDb
   */
  public function getObjectById(int $id): DepartmentDb
  {
      $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
      $sql = "SELECT * FROM department WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam('id', $id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchObject(__CLASS__);
  }

  /**
   * @return DepartmentDb[]
   */
  public function getAllAsObjects(): array|null
  {
      try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
        $sql = "SELECT * FROM department";
        $stmt = $dbh->query($sql);
        $departments = [];
        while ($dep = $stmt->fetchObject(__CLASS__)) {
          $departments[] = $dep;
        }
        $dbh = null;
      } catch (PDOException $e) {
        throw new PDOException('Datenbank sagt nein: ' . $e->getMessage());
      }
    return $departments;
  }

  /**
   * @param string $name
   * @return DepartmentDb
   */
  public function createNewObject(string $name): DepartmentDb
  {
      $dbh = new PDO (DB_DSN, DB_USER, DB_PASSWD);
      $sql = "INSERT INTO department (id, name) VALUES (NULL, :name)";
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam('name', $name);
      $stmt->execute();
      $id = $dbh->lastInsertId();
      return new DepartmentDb($id, $name);
  }

  /**
   * @param int $id
   * @return void
   */
  public function delete(int $id): void
  {
      $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
      $sql = "DELETE FROM department WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam('id', $id, PDO::PARAM_INT);
      $stmt->execute();
  }

  /**
   * @return void
   * @throws Exception
   */
  public function updateObject(): void
  {
      try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
        $sql = "UPDATE department SET name = :name WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam('name', $this->name);
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
        $dbh = null;
      } catch (PDOException $e) {
        throw new Exception($e->getMessage());
      }
  }

}