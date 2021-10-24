<?php


class Student {

  private $id;
  private $name;
  private $grades;
  private School $school;

  public function __construct($id, $name, $grades, School $school) {
    $this->id = $id;
    $this->name = $name;
    $this->grades = $grades;
    $this->school = $school;
  }

  public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    }
  }

  public function __set($property, $value) {
    if (property_exists($this, $property)) {
      $this->$property = $value;
    }

    return $this;
  }

  public function encodeToJSON() {
    return json_encode(get_object_vars($this));
  }
}
