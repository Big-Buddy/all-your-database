Select 
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
    group by PostingUserID