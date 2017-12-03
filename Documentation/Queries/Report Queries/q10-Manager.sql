Select 
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
