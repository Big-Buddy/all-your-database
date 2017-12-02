<?php
    // The 10 MySQL queries for the report
    require '../Connection.php';

    class ReportRepository {
        private $connection;
        public function __construct()
        {
            $this->connection = openConnection();
        }

        public function report1() {
	        $sql = "SELECT numAdsCat.PostingUserID, maxAdsCat.Category, maxAdsCat.maxAds FROM ";
            $sql .= "( SELECT MAX(numAds) maxAds, Category FROM ( SELECT count(*) numAds, Category, PostingUserID FROM Ads GROUP BY PostingUserID, Category ) numAdsCat GROUP BY Category ) ";
            $sql .= "maxAdsCat INNER JOIN ";
            $sql .= "(Select count(*) numAds, Category, PostingUserID from Ads group by PostingUserID, Category) ";
            $sql .= "numAdsCat on numAdsCat.Category = maxAdsCat.Category and numAdsCat.numAds = maxAdsCat.maxAds order by numAdsCat.Category ";
            $result = $this->connection->query($sql);
            closeConnection($this->connection);
            return $result;
        }

        public function report2() {
            $sql = "SELECT * FROM Ads WHERE PostingDate >= addDate(now(), INTERVAL -10 DAY)";
            $result = $this->connection->query($sql);
            closeConnection($this->connection);
            return $result;
        }

        public function report3() {
            $sql =  "Select * from Users
                        inner join (
                            Select distinct PostingUserID from Ads
                                where AdType = 'Sell'
                                and Category = 'Clothing'
                                and description like '%jacket%'
                                and description like '%winter%'
                        ) jacketSellers on Users.UserID = jacketSellers.PostingUserID
                        where 	Users.AddressProvince = 'Quebec'";
            $result = $this->connection->query($sql);
            closeConnection($this->connection);
            return $result;
        }

        public function report4() {
        	$sql = "SELECT * FROM Ads WHERE Category IN ('RentElectronics', 'Car', 'Apartment', 'WeddingDresses')";
            $result = $this->connection->query($sql);
            closeConnection($this->connection);
            return $result;

        }

    }
 ?>	