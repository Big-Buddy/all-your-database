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
        $sql .= "WHERE AddressProvince='$province' AND AddressCity='$city' AND Category='$category';";
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
    public function deleteAd($adID){
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
    public function returnResult($sql)
    {
        $result = $this->connection->query($sql);
        return $result;
    }

    public function closeConnection()
    {
        closeConnection($this->connection);
    }

}
?>
