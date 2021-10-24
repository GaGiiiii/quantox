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
  $grades = $student->__get('grades');
  $grades = explode(',', $grades);
  $avg = array_sum($grades) / count($grades);

  return $avg >= 7 ? ['avg' => $avg, 'pass' => 'Pass'] : ['avg' => $avg, 'pass' => 'Fail'];
}

function calculatePassCSMB($student) {
  $grades = $student->__get('grades');
  $grades = explode(',', $grades);

  if (count($grades) > 2) {
    array_splice($grades, array_search(min($grades), $grades), 1);
  }

  $avg = array_sum($grades) / count($grades);

  return max($grades) > 8 ? ['avg' => $avg, 'pass' => 'Pass'] : ['avg' => $avg, 'pass' => 'Fail'];
}

$student = Database::getInstance()->getStudent(clean($_GET['id']));

if (!$student) {
  echo "Student not found";
  die();
}

$school = Database::getInstance()->getSchool($student->__get('school')->__get('id'));
$pass = false;
$result = [];

switch ($school->__get('name')) {
  case 'CSM':
    $passArr = calculatePassCSM($student);

    $result['student_id'] = $student->__get('id');
    $result['name'] = $student->__get('name');
    $result['grades'] = $student->__get('grades');
    $result['average'] = $passArr['avg'];
    $result['final'] = $passArr['pass'];

    $result = json_encode($result);
    echo $result;

    break;
  case 'CSMB':
    $passArr = calculatePassCSMB($student);

    $result['student_id'] = $student->__get('id');
    $result['name'] = $student->__get('name');
    $result['grades'] = $student->__get('grades');
    $result['average'] = $passArr['avg'];
    $result['final'] = $passArr['pass'];

    // $result = xmlrpc_encode($result);
    $result = json_encode($result);
    echo $result;

    break;
  default:
    echo 'Error';
}
