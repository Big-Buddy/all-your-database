<?php

error_reporting(0);
require_once '../../repositories/ReportRepository.php';

$reportRepository = new ReportRepository();
$result = $reportRepository->triggerPayments();

$array =[];
$isSuccess = false;
if ($result == true) {  
    $object = new stdClass();
    $object->code = "0";
    $object->message =  "Payments sent for processing.";  
    array_push($array, $object);
    $isSuccess = true;
}

if ($isSuccess) {
    echo json_encode($array);
} else {
    header('HTTP/1.1 500 Server Error');
    die(json_encode(array('message' => 'Payments could not be sent for processing.')));
}

?>
