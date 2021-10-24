<?php

require_once 'classes/Database.php';
require_once 'classes/School.php';
require_once 'classes/Student.php';

function clean($input) {
  $input = trim($input);
  $input = str_replace('"', "", $input);
  $input = str_replace("'", "", $input);
  $input = htmlspecialchars($input); // Mora ispod str_replace jer htmlspecialchars pretvara " u &nesto; i onda ga str_replace ne nadje

  return $input;
}

$student = Database::getInstance()->getStudent(clean($_GET['id']));

if (!$student) {
  echo "Student not found";
  die();
}

$school = Database::getInstance()->getSchool($student->__get('school')->__get('id'));
echo $school->calculatePass($student);