<?php

require_once 'School.php';

class CMSSchool extends School {
  public function calculatePass($student) {
    $grades = explode(',', $student->__get('grades'));
    $avg = array_sum($grades) / count($grades);

    $result['student_id'] = $student->__get('id');
    $result['name'] = $student->__get('name');
    $result['grades'] = $student->__get('grades');
    $result['average'] = round($avg, 2);
    $result['final'] = $avg >= 7 ? 'Pass' : 'Fail';

    header('Content-Type: application/json; charset=utf-8');
    return json_encode($result);
  }
}
