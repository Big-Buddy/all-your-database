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
            return $this->returnResult($sql);
        }

        public function report2() {
            $sql = "SELECT * FROM Ads WHERE PostingDate >= addDate(now(), INTERVAL -10 DAY)";
            return $this->returnResult($sql);
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
            return $this->returnResult($sql);
        }

        public function report4() {
        	$sql = "SELECT * FROM Ads WHERE Category IN ('RentElectronics', 'Car', 'Apartment', 'WeddingDresses')";
            return $this->returnResult($sql);
        }

        public function report5() {
            $sql = "Select numRatingsCat.PostingUserID, maxRatingsCat.Category, maxRatingsCat.maxRating,  Users.*
                        from (
                            Select max(Rating) maxRating, Category from (
                                select PostingUserID, adID, Rating, Category from Ads
                                    inner join Ratings on AdIDBeingRated = adID
                            ) numRatingsCat
                            group by Category
                        ) maxRatingsCat
                        inner join 
                            (select PostingUserID, adID, Rating, Category from Ads inner join Ratings on AdIDBeingRated = adID) numRatingsCat
                        on numRatingsCat.Category = maxRatingsCat.Category and numRatingsCat.Rating = maxRatingsCat.maxRating
                        inner join Users on Users.UserID = numRatingsCat.PostingUserID
                        where AddressCity like '%%'";
            return $this->returnResult($sql);
        }

        public function report6() {
            $sql = "Select ManagerUserID, Stores.StoreID, sum(Payments.AmountInCadCents) sumPayments, count(*) numPayments from Stores
                            inner join RentedSpaces on RentedSpaces.StoreID = Stores.StoreID
                        inner join Payments on Payments.RentedSpaceID = RentedSpaces.RentedSpaceID
                        inner join Ads on Ads.AdID = RentedSpaces.AdID
                            where ManagerUserID like '%%'
                        group by ManagerUserID, Stores.StoreID
                        order by sumPayments desc";
            return $this->returnResult($sql);
        }

        public function report7() {
            $sql = "Select Results.*, (Results.sumAdPrices - Results.sumPayments) Profitability from
                        ( 
                            Select 
                                    StrategicLocation, 
                                    if(DAYOFWEEK(RentedSpaces.DateRented) = 7 or DAYOFWEEK(RentedSpaces.DateRented) = 1, 1, 0) as isWeekend, 
                                    if(
                                        DAYOFWEEK(RentedSpaces.DateRented) = 7 or DAYOFWEEK(RentedSpaces.DateRented) = 1, -- If it's a weekend, use weekend rates. else use weekday rates
                                        sum(RentedSpaces.HoursRented*15 + if (RentedSpaces.DeliveryServices = 1, RentedSpaces.HoursRented*10, 0)), -- if there's delivery services, add delivery service rates
                                        sum(RentedSpaces.HoursRented*10 + if (RentedSpaces.DeliveryServices = 1, RentedSpaces.HoursRented*5, 0))
                                    ) as sumPayments, 
                                    sum(Ads.PriceInCADCents) as sumAdPrices 
                                from RentedSpaces
                                inner join Payments on Payments.RentedSpaceID = RentedSpaces.RentedSpaceID
                                inner join Stores on Stores.StoreID = RentedSpaces.StoreID
                                inner join Ads on Ads.AdID = RentedSpaces.AdID
                                where Stores.StrategicLocation in ('SL1','SL2')
                                group by StrategicLocation, isWeekend
                        ) Results";
            return $this->returnResult($sql);
        }

        public function returnResult($sql) {
            $result = $this->connection->query($sql);
            closeConnection($this->connection);
            return $result;
        }

    }
 ?>	
