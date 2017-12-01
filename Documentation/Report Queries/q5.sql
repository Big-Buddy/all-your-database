Select numRatingsCat.PostingUserID, maxRatingsCat.Category, maxRatingsCat.maxRating,  Users.*
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
    where AddressCity like '%%'