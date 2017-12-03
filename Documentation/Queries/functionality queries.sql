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