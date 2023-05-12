<?php

abstract class Department implements Saveable
{
  protected int $id;
  protected string $name;
  /**
   * @var Employee[]
   */
  protected array $employees = [];

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

  public function buildAllEmployeesByDepartment($department): void
  {
    $employees = [];
    $this->employees = $this->employees;
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