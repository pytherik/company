<?php

class HtmlHelper
{
  /**
   * @param array $allObjects
   * @param string $name
   * @param int $preselected
   * @return string
   */
    public static function getSelectOption(array $allObjects, string $name, int $preselected): string
    {
      $html = "select name='$name'>/n";
      foreach($allObjects as $object) {
        $selected = ($preselected === $object->getId()) ? 'selected' : '';
        $html .= "<option value='" . $object->getId() . "'selected>" . $object->getName() . "</option>\n";
    }
    $html .= "</select>\n";
      return $html;
    }
}