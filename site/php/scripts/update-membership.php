<?php

//error_reporting(0);
require_once '../repositories/UserRepository.php';

$userRepository = new UserRepository();

$object = new stdClass();

//$object->payingUserID = $_POST["payingUserID"];
//$object->rentedSpaceID = $_POST["rentedSpaceID"];
//$object->amountInCADCents = $_POST["amountInCADCents"];
//$object->cardNumber = $_POST["cardNumber"];
//$object->cardExpiryDate = $_POST["cardExpiryDate"];
//$object->cardSecurityCode = $_POST["cardSecurityCode"];
//$object->cardholderNumber = $_POST["cardholderNumber"];
//$object->cardCompany = $_POST["cardCompany"];
//$object->cardType = $_POST["cardType"];

$object->payingUserID = 'teh-tom';
$object->rentedSpaceID = null;
$object->amountInCADCents = 100;
$object->cardNumber = '1234123412341234';
$object->cardExpiryDate = '2017-01-01';
$object->cardSecurityCode = '123';
$object->cardholderName = 'adriel';
$object->cardCompany = 'Visa';
$object->cardType = 'Debit';

$result = $userRepository->updateMembership($object);
$isSuccess = false;

if ($result) {
    $isSuccess = true;
}

if ($isSuccess) {
    header('HTTP/1.1 200 Action Script');
    echo json_encode(array('message' => 'Ad successfully updated'));
} else {
    header('HTTP/1.1 500 Server Error');
    die(json_encode(array('message' => 'Something went wrong on our end')));
}

?>
