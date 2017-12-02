<?php
    // The 10 MySQL queries for the report
    require '../Connection.php';

    class ReportRepository {
        private $connection;
        public function __construct()
        {
            $this->connection = openConnection();
        }

        public function report1() 
        {
	        $sql = "SELECT numAdsCat.PostingUserID, maxAdsCat.Category, maxAdsCat.maxAds FROM ";
            $sql .= "( SELECT MAX(numAds) maxAds, Category FROM ( SELECT count(*) numAds, Category, PostingUserID FROM Ads GROUP BY PostingUserID, Category ) numAdsCat GROUP BY Category ) ";
            $sql .= "maxAdsCat INNER JOIN ";
            $sql .= "(Select count(*) numAds, Category, PostingUserID from Ads group by PostingUserID, Category) ";
            $sql .= "numAdsCat on numAdsCat.Category = maxAdsCat.Category and numAdsCat.numAds = maxAdsCat.maxAds order by numAdsCat.Category ";
            return $this->returnResult($sql);
        }

        public function report2() 
        {
            $sql = "SELECT * FROM Ads WHERE PostingDate >= addDate(now(), INTERVAL -10 DAY)";
            return $this->returnResult($sql);
        }

        public function report3() 
        {
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

        public function report4() 
        {
        	$sql = "SELECT * FROM Ads WHERE Category IN ('RentElectronics', 'Car', 'Apartment', 'WeddingDresses')";
            return $this->returnResult($sql);
        }

        public function report5() 
        {
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

        public function report6() 
        {
            $sql = "Select ManagerUserID, Stores.StoreID, sum(Payments.AmountInCadCents) sumPayments, count(*) numPayments from Stores
                            inner join RentedSpaces on RentedSpaces.StoreID = Stores.StoreID
                        inner join Payments on Payments.RentedSpaceID = RentedSpaces.RentedSpaceID
                        inner join Ads on Ads.AdID = RentedSpaces.AdID
                            where ManagerUserID like '%%'
                        group by ManagerUserID, Stores.StoreID
                        order by sumPayments desc";
            return $this->returnResult($sql);
        }

        public function report7() 
        {
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

        public function report8() 
        {
            $sql = "Select Stores.StoreID, Stores.StoreName, Ads.Category from Stores
                        inner join Users on Users.UserID = Stores.ManagerUserID
                    inner join RentedSpaces on RentedSpaces.StoreID = Stores.StoreID
                    inner join Ads on Ads.AdID = RentedSpaces.AdID
                    where Users.AddressProvince like '%%'
                    group by Stores.StoreID, Stores.StoreName, Ads.Category";
            return $this->returnResult($sql);
        }

        public function report9() 
        {
            $sql = "Select DateRented, 
                            if(
                                DAYOFWEEK(RentedSpaces.DateRented) = 7 or DAYOFWEEK(RentedSpaces.DateRented) = 1,
                                RentedSpaces.HoursRented * 10,
                                RentedSpaces.HoursRented * 5
                            ) DeliveryCosts
                        from RentedSpaces
                            where AdID in (Select AdID from Ads where PostingUserID like '%%')
                        and RentedSpaces.DateRented > addDate(now(), INTERVAL -7 DAY)
                        order by DateRented desc";
            return $this->returnResult($sql);
        }

        public function report10Admin() 
        {
            $sql = "Select Users.UserID, Users.Email, ('Yes') as isAdmin, ('Yes') as canDoAdminStuff from Users
                    where UserType = 'Admin'";
            return $this->returnResult($sql);
        }

        public function report10Buyer() 
        {
            $sql = "Select 
                        Users.UserID, 
                        Users.Email, 
                        count(distinct Ads.AdID) numAdsBuying, 
                        if(sum(Ads.PriceInCADCents) is null, 0, sum(Ads.PriceInCADCents)) priceAllAds,
                        if(sum(Payments.AmountInCADCents) is null, 0, sum(Payments.AmountInCADCents)) sumAllPayments
                    from Users
                    inner join Ads on Users.UserID = Ads.PostingUserID
                    left join Payments on Payments.PayingUserID = Users.UserID
                    where Users.UserType = 'Regular'
                    and Ads.AdType = 'Buy'
                    group by PostingUserID";
            return $this->returnResult($sql);

        }

        public function report10Manager() 
        {
            $sql = "Select 
                            ManagerUserID, 
                            Users.Email,
                            count(*) numStores,
                            sum(Payments.AmountInCADCents) sumPaymentsReceived,
                            sum(Ads.PriceInCADCents) sumAdPricesInStore
                        from Users
                        inner join Stores on Stores.ManagerUserID = Users.UserID
                        inner join RentedSpaces on RentedSpaces.StoreID = Stores.StoreID
                        inner join Ads on Ads.AdID = RentedSpaces.AdID
                        inner join Payments on Payments.RentedSpaceID = RentedSpaces.RentedSpaceID
                        where Users.UserType = 'StoreManager'
                        group by Stores.ManagerUserID
                    ";
            return $this->returnResult($sql);

        }

        public function report10Seller() 
        {
            $sql = "Select  
                        Results.UserID,
                        Results.Email,
                        Results.numAds,
                        if(Results.sumServicePayments is null, 0, Results.sumServicePayments) sumServicePayments,
                        if(Results.sumAdPrices is null, 0, Results.sumAdPrices) sumAdPrices,        
                        if(Results.sumAdPrices is null, 0, Results.sumAdPrices) - if(Results.sumServicePayments is null, 0, Results.sumServicePayments) profitability 
                    from ( 
                        Select 
                                Users.UserID,
                                Users.Email,
                                count(distinct Ads.AdID) numAds,
                                if(
                                    DAYOFWEEK(RentedSpaces.DateRented) = 7 or DAYOFWEEK(RentedSpaces.DateRented) = 1, 
                                    sum(RentedSpaces.HoursRented*15 + if (RentedSpaces.DeliveryServices = 1, RentedSpaces.HoursRented*10, 0)), 
                                    sum(RentedSpaces.HoursRented*10 + if (RentedSpaces.DeliveryServices = 1, RentedSpaces.HoursRented*5, 0))
                                ) as sumServicePayments, 
                                sum(Ads.PriceInCADCents) as sumAdPrices 
                            from Users
                            inner join Ads on Ads.PostingUserID = Users.UserID
                            left join RentedSpaces on RentedSpaces.AdID = Ads.AdID
                            where Users.UserType = 'Regular'
                            and Ads.AdType = 'Sell'
                            group by Ads.PostingUserID
                    ) Results";
            return $this->returnResult($sql);

        }

        public function returnResult($sql) 
        {
            $result = $this->connection->query($sql);
            closeConnection($this->connection);
            return $result;
        }

    }
 ?>	
