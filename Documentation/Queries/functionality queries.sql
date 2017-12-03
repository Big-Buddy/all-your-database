/* Retrieves all the Ads from the DB and sorts them by DaysToPromote (which gives them their rank) */
/* To be filtered client side by province, city, and category */
SELECT *
FROM Ads
ORDER BY DaysToPromote DESC;

/* Forces the BackupPayments event to occur when desired by an admin */
DELETE FROM ExternalBackupPayments;
INSERT INTO ExternalBackupPayments
    SELECT * FROM Payments;

/* Returns the list of all payments for viewing by an admin, sorts by date posted */
SELECT *
FROM Payments
ORDER BY PaymentDate ASC;

/* Insert new rating for an ID */
INSERT INTO Ratings (AdIDBeingRated, UserIDRatingAd, Rating)
VALUES (AD_IN_CONTEXT, USER_THAT_IS_RATING, STARS_GIVEN);

/* Delete an ad and all rows that reference it */
DELETE FROM Ratings
WHERE AdIDBeingRated = AD_BEING_DELETED;

UPDATE Payments
SET Payments.RentedSpaceID = NULL
WHERE PayingUserID = 
	(SELECT Ads.PostingUserID 
		FROM RentedSpaces INNER JOIN Ads ON RentedSpaces.AdID = Ads.AdID
		WHERE RentedSpaces.AdID = AD_BEING_DELETED);

DELETE FROM RentedSpaces
WHERE AdID = AD_BEING_DELETED;

DELETE FROM Images
WHERE AdID = AD_BEING_DELETED;

DELETE FROM Ads
WHERE AdID = AD_BEING_DELETED;
