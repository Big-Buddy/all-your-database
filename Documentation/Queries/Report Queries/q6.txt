Select ManagerUserID, Stores.StoreID, sum(Payments.AmountInCadCents) sumPayments, count(*) numPayments from Stores
	inner join RentedSpaces on RentedSpaces.StoreID = Stores.StoreID
    inner join Payments on Payments.RentedSpaceID = RentedSpaces.RentedSpaceID
    inner join Ads on Ads.AdID = RentedSpaces.AdID
	where ManagerUserID like '%%'
    group by ManagerUserID, Stores.StoreID
    order by sumPayments desc