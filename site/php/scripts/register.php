<?php
    // error_reporting(0);
    require_once '../repositories/UserRepository.php';

    $userRepository = new UserRepository();
    $user = new stdClass();
    $user->userID = $_POST["userID"];
    $user->password = $_POST["password"];
    $user->userType = $_POST["userType"];
    $user->membershipPlan = $_POST["membershipPlan"];
    $user->phone = $_POST["phone"];
    $user->email = $_POST["email"];
    $user->addressStreet = $_POST["addressStreet"];
    $user->addressCity = $_POST["addressCity"];
    $user->addressProvince = $_POST["addressProvince"];
    $user->addressCountry = $_POST["addressCountry"];
    $user->addressPostalCode = $_POST["addressPostalCode"];
    $result = $userRepository->createNewUser($user);

    if(!$result) {
        header('HTTP/1.1 401 Client Error');
        die(json_encode(array('message' => 'That username is already taken')));
        return;
    }  else {
        echo json_encode($user);
    }
?>
