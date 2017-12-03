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

/* Post an ad and insert and supporting data (Payments, rentedspaces), THE SEQUENCE OF THESE INSERTS IS IMPORTANT! */
/* Standard ad insert */
INSERT INTO Ads (PostingUserID, PostingDate, DaysToPromote, AdType, Title, Description, PriceInCADCents, Category)
VALUES (THIS_USER, THE_DATE, SELECTED_PROMOTION, TYPE_OF_AD, TITLE, DESCRIPTION, PRICE, SELECTED_CATEGORY); /* SELECTED_PROMOTION is 0 in the case that no promotion is chosen, price can be zero in any case BUT in the case of "XseekingY type ad" is MUST be zero */
INSERT INTO Images (FilePath, AdID)
VALUES (THE_PATH, THE_AD_THAT_WAS_JUST_INSERTED);

/* If the user wants to rent a physical space for their ad, extra form to select a space, date, and # of hours to rent, and if they want delivery services to be enabled, THIS INFO NEEDS TO BE STORED FOR THE PAYMENT */
INSERT INTO RentedSpaces (AdID, StoreID, DateRented, HoursRented, DeliveryServices)
VALUES (AD_THAT_WAS_JUST_INSERTED, SELECTED_STORE, THE_DATE, NUM_HOURS, DELIVERY_01);

/* If a promotion or rented space is selected, extra form to submit payment info */
INSERT INTO Payments (PayingUserID, RentedSpaceID, AmountInCADCents, CardNumber, CardExpiryDate, CardSecurityCode, CardholderName, CardCompany, CardType, PaymentDate)
VALUE (THIS_USER, RENTED_SPACE_THAT_WAS_JUST_INSERTED, TOTAL_COST, CARD_NUM, CARD_EXP, CARD_CODE, CARD_NAME, CARD_COMP, "Credit", THE_DATE);


