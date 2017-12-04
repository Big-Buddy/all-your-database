<?php
// MYSQL queries for the User Relation
require '../Connection.php';

class AdRepository {
    private $connection;
    public function __construct()
    {
        $this->connection = openConnection();
    }

    public function getAds($province, $city, $category)
    {
        $sql = "SELECT * FROM Ads ";
        $sql .= "INNER JOIN Users ON Ads.PostingUserID = Users.UserID ";
        $sql .= "WHERE AddressProvince='$province' AND AddressCity='$city' AND Category='$category' ORDER BY Ads.DaysToPromote DESC;";
        $result = $this->connection->query($sql);
        $this->closeConnection();
        return $result;
    }

    public function getAdsForUser($username)
    {
        $sql = "SELECT * FROM Ads ";
        $sql .= "INNER JOIN Users ON Ads.PostingUserID = Users.UserID ";
        $sql .= "WHERE PostingUserID='$username';";
        $result = $this->connection->query($sql);
        $this->closeConnection();
        return $result;
    }

    public function deleteAd($adID)
    {
        /* Delete an ad and all rows that reference it */
        $sql = "DELETE FROM Ratings ";
        $sql .= "WHERE AdIDBeingRated = $adID; ";

        $sql .= "UPDATE Payments ";
        $sql .= "SET Payments.RentedSpaceID = NULL ";
        $sql .= "WHERE PayingUserID = (SELECT Ads.PostingUserID FROM RentedSpaces INNER JOIN Ads ON RentedSpaces.AdID = Ads.AdID WHERE RentedSpaces.AdID = $adID); ";

        $sql .= "DELETE FROM RentedSpaces ";
        $sql .= "WHERE AdID = $adID; ";

        $sql .= "DELETE FROM Images ";
        $sql .= "WHERE AdID = $adID; ";

        $sql .= "DELETE FROM Ads ";
        $sql .= "WHERE AdID = $adID; ";
        $result = $this->connection->multi_query($sql);          
        $this->closeConnection();
        return $result;
    }

    public function postAd($ad)
    {
        $adID = null;
        $rentedSpaceID = null;

        $sqlInsertAd = "INSERT INTO `ads`(`PostingUserID`, `PostingDate`, `DaysToPromote`, `AdType`, `Title`, `Description`, `PriceInCADCents`, `Category`) ";
        $sqlInsertAd .= "VALUES ('$ad->postingUserID','$ad->postingDate','$ad->daysToPromote','$ad->adType','$ad->title','$ad->description','$ad->priceInCADCents','$ad->category'); ";
        $resultInsertAd = $this->returnResult($sqlInsertAd);
        if ($resultInsertAd) {
            $sql = "SELECT AdID FROM Ads ORDER BY AdId DESC LIMIT 1";
            $result = $this->returnResult($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $adID = $row['AdID'];
                }
            }
        }
        
        $sqlInsertImageAndRentedSpace = "INSERT INTO Images ('FilePath', 'AdID') VALUES ('$ad->filePath','$adID'); ";
        $sqlInsertImageAndRentedSpace .= "INSERT INTO `rentedspaces`(AdID`, `StoreID`, `DateRented`, `HoursRented`, `DeliveryServices`) ";
        $sqlInsertImageAndRentedSpace .= "VALUES ('$adID','$ad->storeID', '$ad->dateRented', '$ad->hoursRented', '$ad->deliveryServices'); ";
        $resultInsertRentedSpace = $this->returnResultOfMultiQuery($sqlInsertImageAndRentedSpace);

        if ($resultInsertRentedSpace) {
            $sql = "SELECT RentedSpaceID FROM rentedSpaces ORDER BY RentedSpaceID DESC LIMIT 1";
            $result = $this->returnResult($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rentedSpaceID = $row['RentedSpaceID'];
                }
            }
        }

        $sqlInsertPayment = "INSERT INTO `payments`(`PayingUserID`, `RentedSpaceID`, `AmountInCADCents`, `CardNumber`, `CardExpiryDate`, `CardSecurityCode`, `CardholderName`, `CardCompany`, `CardType`, `PaymentDate`) ";
        $sqlInsertPayment .= "VALUES ('$ad->postingUserID', '$rentedSpaceID', '$ad->amountInCADCents', '$ad->cardNumber', '$ad->cardExpiryDate', '$ad->cardSecurityCode', '$ad->cardholderNumber', '$ad->cardCompany', '$ad->cardType', '$ad->paymentDate') ";
        $result = $this->returnResult($sqlInsertPayment);
        $this->closeConnection();
        return $result;
    }

    public function editAd($ad)
    {
        $sqlUpdateAd = "UPDATE `ads` SET PostingUserID`='$ad->postingUserID',`PostingDate`='$ad->postingDate',`DaysToPromote`='$ad->daysToPromote',`AdType`='$ad->adType',`Title`='$ad->title',`Description`='$ad->description',`PriceInCADCents`='$ad->priceInCADCents',`Category`='$ad->category' ";
        $sqlUpdateAd .= "WHERE AdID='$ad->ID'; ";
        $sqlUpdateAd .= "UPDATE `rentedspaces` SET DateRented`='$ad->dateRented',`HoursRented`='$ad->hoursRented',`DeliveryServices`='$ad->deliveryServices' ";
        $sqlUpdateAd .= "WHERE AdID='$ad->ID'; ";
        $sqlUpdateAd .= "UPDATE `payments` SET `PaymentID`='$ad->paymentID',`AmountInCADCents`='$ad->amountInCADCents',`CardNumber`='$ad->cardNumber',`CardExpiryDate`='$ad->cardExpiryDate',`CardSecurityCode`='$ad->cardSecurityCode',`CardholderName`='$ad->cardholderName',`CardCompany`='$ad->cardCompany',`CardType`='$ad->cardType',`PaymentDate`='$ad->paymentDate'";
        $sqlUpdateAd .= "WHERE PaymentID='$ad->paymentID'";
        $result = $this->returnResultOfMultiQuery($sqlUpdateAd);
        $this->closeConnection();
        return $result;
    }

    public function returnResult($sql)
    {
        $result = $this->connection->query($sql);
        return $result;
    }

    public function returnResultOfMultiQuery($sql)
    {
        $result = $this->connection->multi_query($sql);
        return $result;
    }

    public function closeConnection()
    {
        closeConnection($this->connection);
    }

}
?>
