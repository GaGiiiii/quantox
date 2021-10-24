<?php

require_once 'School.php';

class CMSBSchool extends School {
  public function calculatePass($student) {
    $grades = explode(',', $student->__get('grades'));

    if (count($grades) > 2) {
      array_splice($grades, array_search(min($grades), $grades), 1);
    }

    $avg = array_sum($grades) / count($grades);

    $result['student_id'] = $student->__get('id');
    $result['name'] = $student->__get('name');
    $result['grades'] = implode(',', $grades);
    $result['average'] = round($avg, 2);
    $result['final'] =  max($grades) > 8 ? 'Pass' : 'Fail';

    // header("Content-type: text/xml; charset=utf-8");
    // return xmlrpc_encode($result);

    header('Content-Type: application/json; charset=utf-8');
    return json_encode($result);
  }
}
