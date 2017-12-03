<?php
require_once '../../repositories/ReportRepository.php';

$reportRepository = new ReportRepository();
$city = $_POST['city'];
$result = $reportRepository->report5($city);
$isSuccess = false;

$array = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $object = new stdClass();
        $object->postingUserID = $row["PostingUserID"];
        $object->category = $row["Category"];
        $object->maxRating = $row["maxRating"];
        $object->userID = $row["UserID"];
        $object->password = $row["Password"];
        $object->userType = $row["UserType"];
        $object->membershipPlan = $row["MembershipPlan"];
        $object->phone = $row["Phone"];
        $object->email = $row["Email"];
        $object->addressStreet = $row["AddressStreet"];
        $object->addressCity = $row["AddressCity"];
        $object->addressProvince = $row["AddressProvince"];
        $object->addressCountry = $row["AddressCountry"];
        $object->addressPostalCode = $row["AddressPostalCode"];
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
