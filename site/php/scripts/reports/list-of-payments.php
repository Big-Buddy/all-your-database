<?php

error_reporting(0);
require_once '../../repositories/ReportRepository.php';

$reportRepository = new ReportRepository();
$result = $reportRepository->listOfPayments();

$array =[];
$isSuccess = false;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $object = new stdClass();
        $object->paymentID = $row["PaymentID"];
        $object->payingUserID = $row["PayingUserID"];
        $object->rentedSpaceID = $row["RentedSpaceID"];
        $object->amountInCADCents = $row["AmountInCADCents"];
        $object->cardNumber = $row["CardNumber"];
        $object->cardExpiryDate = $row["CardExpiryDate"];
        $object->cardSecurityCode = $row["CardSecurityCode"];
        $object->cardholderName = $row["CardholderName"];
        $object->cardCompany = $row["CardCompany"];
        $object->cardType = $row["CardType"];
        $object->paymentDate = $row["PaymentDate"];
        array_push($array, $object);
    }
    $isSuccess = true;
}

if ($isSuccess) {
    echo json_encode($array);
} else {
    header('HTTP/1.1 500 Server Error');
    die(json_encode(array('message' => 'Could not display list of payments.')));
}

?>
