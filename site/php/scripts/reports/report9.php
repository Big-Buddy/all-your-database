<?php
require_once '../../repositories/ReportRepository.php';

$reportRepository = new ReportRepository();
$seller = $_POST['seller'];
$result = $reportRepository->report9($seller);
$isSuccess = false;

$array = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $object = new stdClass();
        $object->dateRented = $row["DateRented"];
        $object->deliveryCosts = $row["DeliveryCosts"];
        array_push($array, $object);
    }
    $isSuccess = true;
}

if ($isSuccess) {
    echo json_encode($array);
} else {
    header('HTTP/1.1 500 Server Error');
    die(json_encode(array('message' => 'No value was returned')));
}
?>
