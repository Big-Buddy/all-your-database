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
        if($province && $city && $category){
            $sql .= "WHERE AddressProvince='$province' AND AddressCity='$city' AND Category='$category' ORDER BY Ads.DaysToPromote ASC;";
        }
        else{
            //show all ads for all regions
            $sql .= "ORDER BY Ads.DaysToPromote ASC;";
        }
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
    public function getAdRank($adID){
        $sql = "SET @Position=0; ";
        $sql .= "SELECT AdPos.PositionInCategories from Ads
                inner join (
                    Select AdID, PostingUserID, Category, PostingDate,
                    @Position:= if(@prevCat != Category, 0, @Position+1) as PositionInCategories, 
                    @prevCat:=Category
                    from Ads
                    order by Category, PostingDate desc) AdPos on AdPos.AdID = Ads.AdID
                where Ads.AdID = '$adID';";
        $result = $this->connection->multi_query($sql);
        $this->closeConnection();
        return $result;
    }
    public function getAdsForAdID($adID)
    {
        $sql = "SELECT * FROM Ads ";
        $sql .= "INNER JOIN Users ON Ads.PostingUserID = Users.UserID ";
        $sql .= "WHERE AdID='$adID';";
        $result = $this->connection->query($sql);
        $this->closeConnection();
        return $result;
    }

    public function getMostRecentAd()
    {
        $adID = null;
        $sql = "SELECT AdID FROM Ads ORDER BY AdId DESC LIMIT 1";
        $result = $this->returnResult($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $adID = $row['AdID'];
            }
        }
        // $this->closeConnection();
        return $adID;
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

    public function createAd($ad)
    {
        $adID = null;
        $rentedSpaceID = null;

        $sqlInsertAd = "INSERT INTO `ads`(`PostingUserID`, `PostingDate`, `DaysToPromote`, `AdType`, `Title`, `Description`, `PriceInCADCents`, `Category`) ";
        $sqlInsertAd .= "VALUES ('$ad->postingUserID','$ad->postingDate','$ad->daysToPromote','$ad->adType','$ad->title','$ad->description','$ad->priceInCADCents','$ad->category'); ";
        $result = $this->returnResult($sqlInsertAd);

        if ($result) {
            $adID = $this->getMostRecentAd();
        }

        if ($ad->isRenting != 'false') {
            $sqlInsertRentedSpace = "INSERT INTO `rentedspaces`(AdID`, `StoreID`, `DateRented`, `HoursRented`, `DeliveryServices`) ";
            $sqlInsertRentedSpace .= "VALUES ('$adID','$ad->storeID', '$ad->dateRented', '$ad->hoursRented', '$ad->deliveryServices'); ";
            $result = $this->returnResult($sqlInsertRentedSpace);

            if ($result) {
                $sql = "SELECT RentedSpaceID FROM rentedSpaces ORDER BY RentedSpaceID DESC LIMIT 1";
                $result = $this->returnResult($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $rentedSpaceID = $row['RentedSpaceID'];
                    }
                }
            }
        }

        if ($ad->daysToPromote != '0') {
            $sqlInsertPayment = "INSERT INTO `payments`(`PayingUserID`, `RentedSpaceID`, `AmountInCADCents`, `CardNumber`, `CardExpiryDate`, `CardSecurityCode`, `CardholderName`, `CardCompany`, `CardType`, `PaymentDate`) ";
            $sqlInsertPayment .= "VALUES ('$ad->postingUserID', '$rentedSpaceID', '$ad->amountInCADCents', '$ad->cardNumber', '$ad->cardExpiryDate', '$ad->cardSecurityCode', '$ad->cardholderNumber', '$ad->cardCompany', '$ad->cardType', '$ad->paymentDate') ";
            $result = $this->returnResult($sqlInsertPayment);
        }

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
