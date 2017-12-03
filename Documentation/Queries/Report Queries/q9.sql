Select DateRented, 
	if(
		DAYOFWEEK(RentedSpaces.DateRented) = 7 or DAYOFWEEK(RentedSpaces.DateRented) = 1,
        RentedSpaces.HoursRented * 10,
        RentedSpaces.HoursRented * 5
	) DeliveryCosts
    from RentedSpaces
	where AdID in (Select AdID from Ads where PostingUserID like '%%')
    and RentedSpaces.DateRented > addDate(now(), INTERVAL -7 DAY)
    order by DateRented desc