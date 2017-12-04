Select * from Users
	inner join (
		Select distinct PostingUserID from Ads
			where AdType = 'Sell'
			and Category = 'Clothing'
			and description like '%jacket%'
			and description like '%winter%'
            and description like '%men%s%'
	) jacketSellers on Users.UserID = jacketSellers.PostingUserID
	where 	Users.AddressProvince = 'Quebec'