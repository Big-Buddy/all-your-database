Select numAdsCat.PostingUserID, maxAdsCat.Category, maxAdsCat.maxAds
	from (
		Select max(numAds) maxAds, Category from (
			Select count(*) numAds, Category, PostingUserID from Ads 
				group by PostingUserID, Category
		) numAdsCat
		group by Category
	) maxAdsCat 
	inner join 
		(Select count(*) numAds, Category, PostingUserID from Ads group by PostingUserID, Category) numAdsCat 
	on numAdsCat.Category = maxAdsCat.Category and numAdsCat.numAds = maxAdsCat.maxAds
    order by numAdsCat.Category