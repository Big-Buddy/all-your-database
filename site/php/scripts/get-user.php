<?php

    error_reporting(0);
    require_once '../repositories/UserRepository.php';

    $username = $_POST['username'];

    $userRepository = new UserRepository();

    $result = $userRepository->getUserObject($username);
    $isSuccess = false;
    $array = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $object = new stdClass();
            $object->userID = $row["UserID"];
            $object->password = $row["Password"];
            $object->userType = $row["UserType"];
            $object->membershipPlan = $row["MembershipPlan"];
            $object->phone = $row["Phone"];
            $object->email = $row["Email"];
            $object->addressStreet = $row["AddressStreet"];
            $object->addressCity = $row["AddressCity"];
            $object->addressProvince = $row["AddressProvince"];
            $object->addressCountry = $row["AddressCountry"];
            $object->addressPostalCode = $row["AddressPostalCode"];
            array_push($array, $object);
        }
        $isSuccess = true;
    }

    if ($isSuccess) {
        echo json_encode($array);
    } else {
        header('HTTP/1.1 401 Server Error');
        die(json_encode(array('message' => 'Could not return a user object.')));
    }

?>
