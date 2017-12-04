<?php

error_reporting(0);
require_once '../repositories/AdRepository.php';

$adRepository = new AdRepository();
$adID = $_POST['adID'];
$username = $_POST['username'];
$rating = $_POST['rating'];
$result = $adRepository->sendRating($adID, $username, $rating);

$isSuccess = false;
if ($result) {
    $isSuccess = true;
}

if ($isSuccess) {
    echo json_encode(array('message' => 'Rating received, thank you!'));
} else {
     header('HTTP/1.1 500 Server Error');
    die(json_encode(array('message' => 'Something went wrong on our side')));
}

?>
