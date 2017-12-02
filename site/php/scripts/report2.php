<?php
      require_once '../repositories/ReportRepository.php';
      
      $reportRepository = new ReportRepository();
      $result = $reportRepository->report2();
      $isSuccess = false;

      $array = [];
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $object = new stdClass();
          $object->adID = $row["AdID"];
          $object->postingUserId = $row["PostingUserID"];
          $object->postingDate = $row["PostingDate"];
          $object->daysToPromote = $row["DaysToPromote"];
          $object->adType = $row["AdType"];
          $object->title = $row["Title"];
          $object->description = $row["Description"];
          $object->priceInCADCents = $row["PriceInCADCentes"];
          $object->category = $row["Category"];
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
