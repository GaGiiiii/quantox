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

function calculatePassCSM($student) {
  $grades = $student['students.grades'];
  $grades = explode(',', $grades);
  $avg = array_sum($grades) / count($grades);

  return $avg >= 7 ? ['avg' => $avg, 'pass' => 'Pass'] : ['avg' => $avg, 'pass' => 'Fail'];
}

function calculatePassCSMB($student) {
  $grades = $student['students.grades'];
  $grades = explode(',', $grades);
  $avg = array_sum($grades) / count($grades);

  return $avg >= 7 ? ['avg' => $avg, 'pass' => 'Pass'] : ['avg' => $avg, 'pass' => 'Fail'];
}

$student = Database::getInstance()->getStudent(clean($_GET['id']));

if (!$student) {
  echo "Student not found";
  die();
}

$school = Database::getInstance()->getSchool($student['students.school_id']);
$pass = false;
$result = [];

switch ($school['schools.name']) {
  case 'CSM':
    $passArr = calculatePassCSM($student);

    $result['student_id'] = $student['students.id'];
    $result['name'] = $student['students.name'];
    $result['grades'] = $student['students.grades'];
    $result['average'] = $passArr['avg'];
    $result['final'] = $passArr['pass'];

    $result = json_encode($result);
    break;
  case 'CSMB':
    $passArr = calculatePassCSMB($student);

    $result['student_id'] = $student['students.id'];
    $result['name'] = $student['students.name'];
    $result['grades'] = $student['students.grades'];
    $result['average'] = $passArr['avg'];
    $result['final'] = $passArr['pass'];

    $result = json_encode($result);
    break;
  default:
    echo 'Error';
}

echo $result;
