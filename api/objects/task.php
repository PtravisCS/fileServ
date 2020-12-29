<?php

  class Task {
      
    private $conn;
    private $table_name = "Tasks";
               
    // object properties
    public $id;
    public $title;
    public $description;
    public $assignee;
    public $assigner;
    public $date_assigned;
    public $due_date;

    // constructor with $db as database connection
    public function __construct($db){
      $this->conn = $db;
    }

    function read() {

      $query = "SELECT

    }

  }

?>
