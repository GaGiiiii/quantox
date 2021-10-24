<?php

require_once 'CMSSchool.php';
require_once 'CMSBSchool.php';

class Database extends PDO {
  private $host; // Host
  private $db_name; // DB Name
  private $username; // DB Username
  private $password; // DB Password

  private static $instance = null; // Instanca klase
  public $connection = null; // Konekcija

  private function __construct() {
    $this->host = "localhost"; // Host
    $this->db_name = "quantox"; // DB Name
    $this->username = "root"; // DB Username
    $this->password = ""; // DB Password

    try {
      $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8', $this->username, $this->password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connection->setAttribute(PDO::ATTR_FETCH_TABLE_NAMES, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die("Database didn't connect.");
    }
  }

  public function getConnection() {
    return $this->connection;
  }

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new Database();
    }

    return self::$instance;
  }

  public function populateDB($data) {
    try {
      if ($this->isPopulated()) {
        echo "DB Already Populated";

        return;
      }

      $this->connection->beginTransaction();

      if ($this->populateSchools($data['schools']) && $this->populateStudents($data['students'])) {
        $this->connection->commit();
        echo 'Success';
      } else {
        $this->connection->rollBack();
        echo "Fail";
      }
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      $this->connection->rollBack();
    }
  }

  function populateSchools($schools) {
    foreach ($schools as $school) {
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO schools (name) VALUES (?)");
      $result = $query->execute([
        $school->name,
      ]);

      if ($result == false) {
        return $result;
      }
    }

    return $result;
  }

  function populateStudents($students) {
    foreach ($students as $student) {
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO students (name, grades, school_id) VALUES (?, ?, ?)");
      $result = $query->execute([
        $student->name,
        implode(',', $student->grades),
        $student->school->id,
      ]);

      if ($result == false) {
        return $result;
      }
    }

    return $result;
  }

  public function isPopulated() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `students`");
      $query->execute();
      $students = $query->fetchAll(PDO::FETCH_ASSOC);

      if (!empty($students)) {
        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return true;
    }
  }

  public function getStudent($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `students` WHERE id = :id");
      $query->execute(array(
        ':id' => $id,
      ));

      $row = $query->fetch(PDO::FETCH_ASSOC);

      if ($row) {
        $school = $this->getSchool($row['students.school_id']);
        $student = new Student($row['students.id'], $row['students.name'], $row['students.grades'], $school);

        return $student;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function getSchool($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `schools` WHERE id = :id");
      $query->execute(array(
        ':id' => $id,
      ));

      $row = $query->fetch(PDO::FETCH_ASSOC);

      if ($row) {
        if ($row['schools.name'] === 'CSM') {
          $school = new CMSSchool($row['schools.id'], $row['schools.name']);
        } else {
          $school = new CMSBSchool($row['schools.id'], $row['schools.name']);
        }

        return $school;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function truncateDB() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("TRUNCATE TABLE `students`");
      $query->execute();

      $query = Database::getInstance()->getConnection()->prepare("TRUNCATE TABLE `schools`");
      $query->execute();
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }
}
