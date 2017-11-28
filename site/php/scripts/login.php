<?php
      require_once '../models/User.php';
      //If the user is authenticated, then return the user object
      //Else return a 401 client error
      $email = $_POST['username'];
      $password = $_POST['password'];

      $user = new User();
      $user->email = $email;
      $user->password = $password;
      $result = $user->authenticateUser();

      $authenticatedUser = new User();
      if ($result != null) {
          $authenticatedUser->email = $result['email'];
          $authenticatedUser->password = $result['password'];
          echo json_encode($authenticatedUser);
      } else {
          header('HTTP/1.1 401 Client Error');
          die(json_encode(array('message' => 'Username and password combination do not match any of our records')));
      }
?>
