<?php
require_once '../repositories/ReportRepository.php';

$reportRepository = new ReportRepository();
$result = $reportRepository->report10Seller();
$isSuccess = false;

$array = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $object = new stdClass();
        $object->userID = $row["UserID"];
        $object->email = $row["Email"];
        $object->numAds = $row["numAds"];
        $object->sumServicePayments = $row["sumServicePayments"];
        $object->sumAdPrices = $row["sumAdPrices"];
        $object->profitability = $row["profitability"];
        array_push($array, $object);
    }
    $isSuccess = true;
}

if ($isSuccess) {
    echo json_encode($array);
} else {
    header('HTTP/1.1 500 Server Error');
    die(json_encode(array('message' => 'Something went wrong on our side')));
}
?>
