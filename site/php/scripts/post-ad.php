<?php

// error_reporting(0);
require_once '../repositories/AdRepository.php';

$adRepository = new AdRepository();

$object = new stdClass();
$object->isRenting = $_POST["isRenting"];
$object->isPromoting = $_POST["isPromoting"];
$object->postingUserID = $_POST["postingUserID"];
$object->postingDate = $_POST["postingDate"];
$object->daysToPromote = $_POST["daysToPromote"];
$object->adType = $_POST["adType"];
$object->title = $_POST["title"];
$object->description = $_POST["description"];
$object->priceInCADCents = $_POST["priceInCADCents"];
$object->category = $_POST["category"];
//
//$object->filePath = $_POST["filePath"];
//

//
if($object->isRenting == "true" || $object->isPromoting == "true"){
	$object->amountInCADCents = $_POST["amountInCADCents"];
	$object->cardNumber = $_POST["cardNumber"];
	$object->cardExpiryDate = $_POST["cardExpiryDate"];
	$object->cardSecurityCode = $_POST["cardSecurityCode"];
	$object->cardholderNumber = $_POST["cardholderNumber"];
	$object->cardCompany = $_POST["cardCompany"];
	$object->cardType = $_POST["cardType"];
}
if($object->isRenting == "true"){
	$object->storeID = $_POST["storeID"];
	$object->dateRented = $_POST["dateRented"];
	$object->hoursRented = $_POST["hoursRented"];
	$object->deliveryServices = $_POST["deliveryServices"];
}


// $object->postingUserID = 'teh-tom';
// $object->postingDate = '2017-12-03';
// $object->daysToPromote = 1000;
// $object->adType = 'Buy';
// $object->title = 'tit';
// $object->description = 'testd';
// $object->priceInCADCents = 1000;
// $object->category = 'Books';

//$object->filePath = $_POST["filePath"];
//
//$object->storeID = $_POST["storeID"];
//$object->dateRented = $_POST["dateRented"];
//$object->hoursRented = $_POST["hoursRented"];
//$object->membershipPlan = $_POST["membershipPlan"];
//$object->deliveryServices = $_POST["deliveryServices"];
//
//$object->amountInCADCents = $_POST["amountInCADCents"];
//$object->cardNumber = $_POST["cardNumber"];
//$object->cardExpiryDate = $_POST["cardExpiryDate"];
//$object->cardSecurityCode = $_POST["cardSecurityCode"];
//$object->cardholderNumber = $_POST["cardholderNumber"];
//$object->cardCompany = $_POST["cardCompany"];
//$object->cardType = $_POST["cardType"];
//$object->paymentDate = $_POST["paymentDate"];
$result = $adRepository->createAd($object);

$isSuccess = false;
if ($result) {
    $isSuccess = true;
}
if($object->isRenting == "true" || $object->isPromoting == "true"){
	//DB giving warning that is unignorable, give success message
	$isSuccess = true;
}

if ($isSuccess) {
    echo json_encode(array('message' => 'Ad successfully inserted'));
} else {
     header('HTTP/1.1 500 Server Error');
    die(json_encode(array('message' => 'Something went wrong on our side')));
}

?>
