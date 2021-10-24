<?php

require_once 'classes/Database.php';
require_once 'classes/School.php';
require_once 'classes/Student.php';

function createRandomGrades() {
  $grades = [];
  $numOfGrades = rand(1, 4);

  for ($i = 0; $i < $numOfGrades; $i++) {
    array_push($grades, rand(5, 10));
  }

  return $grades;
}

$school = new School(1, 'CSM');
$school2 = new School(2, 'CSMB');

$student = new Student(0, 'Dragoslav Jankovic', createRandomGrades(), $school);
$student2 = new Student(0, 'Petar Janjusevic', createRandomGrades(), $school2);
$student3 = new Student(0, 'Filip Djordjevic', createRandomGrades(), $school);
$student4 = new Student(0, 'Mateja Ivanovic', createRandomGrades(), $school2);
$student5 = new Student(0, 'Sanja Djukanovic', createRandomGrades(), $school);
$student6 = new Student(0, 'Doroteja Mitrovic', createRandomGrades(), $school2);
$student7 = new Student(0, 'Pavle Jacovic', createRandomGrades(), $school);
$student8 = new Student(0, 'Nikola Djordjevic', createRandomGrades(), $school);
$student9 = new Student(0, 'Mirko Zecic', createRandomGrades(), $school2);
$student10 = new Student(0, 'Jovana Jankovic', createRandomGrades(), $school2);
$student11 = new Student(0, 'Sasa Jevremovic', createRandomGrades(), $school);

$data = [];
$schools = [];
$students = [];

array_push($schools, $school);
array_push($schools, $school2);

array_push($students, $student);
array_push($students, $student2);
array_push($students, $student3);
array_push($students, $student4);
array_push($students, $student5);
array_push($students, $student6);
array_push($students, $student7);
array_push($students, $student8);
array_push($students, $student9);
array_push($students, $student10);
array_push($students, $student11);

$data['schools'] = $schools;
$data['students'] = $students;

Database::getInstance()->populateDB($data);
