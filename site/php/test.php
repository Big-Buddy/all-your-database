<?php
    include './repositories/UserRepository.php';

    $userRepository = new UserRepository();
    $user = new User;
    $user->email = 'adriel@comp.com';
    $user->password = 'comp353';

    $user2 = new User;
    $user2 = $userRepository->authenticateUser($user);

    var_dump($user2);

 ?>
