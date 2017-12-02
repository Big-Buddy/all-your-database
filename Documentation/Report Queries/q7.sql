Select Results.*, (Results.sumAdPrices - Results.sumPayments) profitability from
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
			inner join Stores on Stores.StoreID = RentedSpaces.StoreID
			inner join Ads on Ads.AdID = RentedSpaces.AdID
			where Stores.StrategicLocation in ('SL1','SL4')
			group by StrategicLocation, isWeekend
	) Results