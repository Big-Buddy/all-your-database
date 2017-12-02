<?php
      require_once '../models/User.php';
      // If the user is authenticated, then return the user object
      // Else return a 401 client error
      $email = $_POST['username'];
      $password = $_POST['password'];
      
      $user = new User();
      $result = $user->authenticateUser($email, $password);

      if ($result != null) {
          echo json_encode($user);
      } else {
          header('HTTP/1.1 401 Client Error');
          die(json_encode(array('message' => 'Username and password combination do not match any of our records')));
      }
?>
