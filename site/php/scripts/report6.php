<?php
require_once '../repositories/ReportRepository.php';

$reportRepository = new ReportRepository();
$manager = $_POST['manager'];
$result = $reportRepository->report6($manager);
$isSuccess = false;

$array = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $object = new stdClass();
        $object->managerUserID = $row["ManagerUserID"];
        $object->storeID = $row["StoreID"];
        $object->sumPayments = $row["sumPayments"];
        $object->numPayments = $row["numPayments"];
        array_push($array, $object);
    }
    $isSuccess = true;
}

if ($isSuccess) {
    echo json_encode($array);
} else {
    header('HTTP/1.1 500 Server Error');
    die(json_encode(array('message' => 'Please specify a valid manager ID')));
}
?>
