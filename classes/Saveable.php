<?php

interface Saveable
{
  public function delete(int $id);
  public function store();
  public function getAllAsObjects();
  public function getObjectById(int $id);

}