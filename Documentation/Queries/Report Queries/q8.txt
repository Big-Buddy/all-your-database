Select Stores.StoreID, Stores.StoreName, Ads.Category from Stores
	inner join Users on Users.UserID = Stores.ManagerUserID
    inner join RentedSpaces on RentedSpaces.StoreID = Stores.StoreID
    inner join Ads on Ads.AdID = RentedSpaces.AdID
    where Users.AddressProvince like '%%'
    group by Stores.StoreID, Stores.StoreName, Ads.Category