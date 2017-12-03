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
