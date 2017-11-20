<?php
    require 'repositories/UserRepository.php';
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userRepository = new UserRepository();
    $user = new User;
    $user->email = $email;
    $user->password = $password;

    $user2 = new User;
    $user2 = $userRepository->authenticateUser($user);

    echo json_encode($user2);
 ?>
