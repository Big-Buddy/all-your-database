<?php

error_reporting(0);
require_once '../repositories/AdRepository.php';

$username = $_POST['username'];

$adRepository = new AdRepository();

$result = $adRepository->getAdsForUser($username);

$array =[];
$isSuccess = false;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $object = new stdClass();
        $object->adID = $row["AdID"];
        $object->postingUserID = $row["PostingUserID"];
        $object->postingDate = $row["PostingDate"];
        $object->daysToPromote = $row["DaysToPromote"];
        $object->adType = $row["AdType"];
        $object->title = $row["Title"];
        $object->description = $row["Description"];
        $object->priceInCADCents = $row["PriceInCADCents"];
        $object->category = $row["Category"];
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
    die(json_encode(array('message' => 'No ads match the inputs')));
}

?>
