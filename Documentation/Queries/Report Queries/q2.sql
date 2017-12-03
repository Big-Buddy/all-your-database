Select * from Ads
	where PostingDate >= addDate(now(), INTERVAL -10 DAY)