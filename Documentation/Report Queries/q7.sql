Select StrategicLocation, (if(DAYOFWEEK(Payments.PaymentDate) = 7 or DAYOFWEEK(Payments.PaymentDate) = 1, 1, 0)) as isWeekend, sum(Payments.AmountInCADCents) sumPayments from RentedSpaces
	inner join Payments on Payments.RentedSpaceID = RentedSpaces.RentedSpaceID
    inner join Stores on Stores.StoreID = RentedSpaces.StoreID
    where Stores.StrategicLocation in ('SL1','SL4')
    group by StrategicLocation, isWeekend