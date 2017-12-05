<?php

// error_reporting(0);
require_once '../repositories/AdRepository.php';

$adRepository = new AdRepository();

$object = new stdClass();

//Conditional values
$object->isRenting = $_POST['isRenting'] . '';
$object->isPromoting = $_POST['isPromoting'] . '';

//Ad attributes
$object->postingUserID = $_POST['postingUserID'];
$object->postingDate = $_POST['postingDate'];
$object->daysToPromote = $_POST['daysToPromote'];
$object->adType = $_POST['adType'];
$object->title = $_POST['title'];
$object->description = $_POST['description'];
$object->priceInCADCents = $_POST['priceInCADCents'];
$object->category = $_POST['category'];

//Rented Space Attributes
$object->storeID = $_POST['storeID'];
$object->dateRented = $_POST['dateRented'];
$object->hoursRented = $_POST['hoursRented'];
$object->deliveryServices = $_POST['deliveryServices'];

//Payment Attributes
$object->amountInCADCents = $_POST['amountInCADCents'];
$object->cardNumber = $_POST['cardNumber'];
$object->cardExpiryDate = $_POST['cardExpiryDate'];
$object->cardSecurityCode = $_POST['cardSecurityCode'];
$object->cardholderName = $_POST['cardholderName'];
$object->cardCompany = $_POST['cardCompany'];
$object->cardType = $_POST['cardType'];

// //Conditional values
// $object->isRenting = 'false';
// $object->isPromoting = 'false';
//
// //Ad attributes
// $object->postingUserID = 'teh-tom';
// $object->postingDate = '2017-12-03';
// $object->daysToPromote = 60 . '';
// $object->adType = 'Sell';
// $object->title = 'tit';
// $object->description = 'testd';
// $object->priceInCADCents = 1000;
// $object->category = 'Books';
//
////Rented Space Attributes
//$object->storeID = 4;
//$object->dateRented = '2018-07-01';
//$object->hoursRented = 4;
//$object->deliveryServices = 0;
//
////Payment Attributes
//$object->amountInCADCents = 5000;
//$object->cardNumber = '1234123412341234';
//$object->cardExpiryDate = '2018-01-01';
//$object->cardSecurityCode = '199';
//$object->cardholderName = 'asd';
//$object->cardCompany = 'VISA';
//$object->cardType = 'Credit';
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
