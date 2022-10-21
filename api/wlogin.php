<?php
  class authenticator {


    /**
     * Name: 
     *  authenticator
     *
     * parameters:  
     *  $user - String, the username to be tested
     *  $pass - String, the password to be tested
     *
     * Returns:
     *  Boolean - Whether the user was successfully authenticated
     *
     * Description:
     *  Attempts to authenticate a user against the user records in the database
     *
     */
    public static function authenticate($user, $pass) {


      include_once './config/database.php';

      $username = $user;
      $password = $pass;

      if (empty($password)) {
        return -1;
      }

      if (empty($username)) {
        return -1;
      }

      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM Users where username = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($username));
      $data = $q->fetch(PDO::FETCH_ASSOC);
      Database::disconnect(); 

      if (!empty($data['password'])) {
        $recieved = $data['password'];

        if(password_verify($password, $recieved)) {
          $access_level = $data['access_level'];

          return $access_level;
        
        } else {
          return -1;
        }
      } else {
       return -1; 
      }

    }

  }
  
?>
