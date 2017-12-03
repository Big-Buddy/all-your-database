<?php

error_reporting(0);
require_once '../repositories/AdRepository.php';

$adID = $_POST['adID'];

$adRepository = new AdRepository();

$result = $adRepository->deleteAd($adID);

$array =[];
$isSuccess = false;
if ($result == true) {  
    $object = new stdClass();
    $object->code = "0";
    $object->message =  "Ad deleted successfully.";  
    array_push($array, $object);
    $isSuccess = true;
}

if ($isSuccess) {
    echo json_encode($array);
} else {
    header('HTTP/1.1 500 Server Error');
    die(json_encode(array('message' => 'Something went wrong on our end.')));
}

?>
