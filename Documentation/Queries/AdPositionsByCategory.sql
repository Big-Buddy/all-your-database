set @Position = 0;
Select AdPos.AdID, AdPos.Category, AdPos.PositionInCategories, AdPos.PostingUserID from Ads
	inner join (
		Select AdID, PostingUserID, Category, PostingDate,
        @Position:= if(@prevCat != Category, 0, @Position+1) as PositionInCategories, 
        @prevCat:=Category
        from Ads
		order by Category, PostingDate desc) AdPos on AdPos.AdID = Ads.AdID
	where Ads.PostingUserID like '%%'