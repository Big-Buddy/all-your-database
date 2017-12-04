<?php
    error_reporting(0);
    require_once '../repositories/UserRepository.php';

    $username = $_POST['username'];
    $newPlan = $_POST['newPlan'];
    $userRepository = new UserRepository();
    $payment = new stdClass();
    $payment->amountInCADCents = $_POST['AmountInCADCents'];
    $payment->cardNumber = $_POST['CardNumber'];
    $payment->cardExpiryDate = $_POST['CardExpiryDate'];
    $payment->cardSecurityCode = $_POST['CardSecurityCode'];
    $payment->cardholderName = $_POST['CardholderName'];
    $payment->cardCompany = $_POST['CardCompany'];
    $payment->cardType = $_POST['CardType'];
    
    $result = $userRepository->updateMembership($username, $payment, $newPlan);

    if(!$result) {
        header('HTTP/1.1 401 Client Error');
        die(json_encode(array('message' => 'Membership was not able to update')));
        return;
    }  else {
        echo json_encode(array('message' => 'Payment received, thank you'));
    }
?>
