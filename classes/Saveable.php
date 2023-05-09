<?php

interface Saveable
{
  public function delete(int $id);
  public function updateObject();
  public function getAllAsObjects();
  public function getObjectById(int $id);

}