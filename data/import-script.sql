LOAD DATA LOCAL INFILE 'C:/Users/Alex/Desktop/all-your-database/data/Users.csv'
INTO TABLE fvc353_2.Users
FIELDS TERMINATED BY ','
optionally ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(UserID,Password,UserType,MembershipPlan,Phone,Email,AddressStreet,AddressCity,AddressProvince,AddressCountry,AddressPostalCode);


LOAD DATA LOCAL INFILE 'C:/Users/Alex/Desktop/all-your-database/data/Ads.csv'
INTO TABLE fvc353_2.Ads
FIELDS TERMINATED BY ','
optionally ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(AdID,PostingUserID,PostingDate,DaysToPromote,AdType,Title,Description,@PriceInCADCents,Category)
SET PriceInCADCents = nullif(@PriceInCADCents, '');

LOAD DATA LOCAL INFILE 'C:/Users/Alex/Desktop/all-your-database/data/Images.csv'
INTO TABLE fvc353_2.Images
FIELDS TERMINATED BY ','
optionally ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(FilePath,AdID);

LOAD DATA LOCAL INFILE 'C:/Users/Alex/Desktop/all-your-database/data/Stores.csv'
INTO TABLE fvc353_2.Stores
FIELDS TERMINATED BY ','
optionally ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(StoreID,ManagerUserID,StoreName,StrategicLocation);

LOAD DATA LOCAL INFILE 'C:/Users/Alex/Desktop/all-your-database/data/RentedSpaces.csv'
INTO TABLE fvc353_2.RentedSpaces
FIELDS TERMINATED BY ','
optionally ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(RentedSpaceID,AdID,StoreID,DateRented,HoursRented,DeliveryServices);

LOAD DATA LOCAL INFILE 'C:/Users/Alex/Desktop/all-your-database/data/Payments.csv'
INTO TABLE fvc353_2.Payments
FIELDS TERMINATED BY ','
optionally ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(PaymentID,PayingUserID,@RentedSpaceID,AmountInCADCents,CardNumber,@CardExpiryDate,@CardSecurityCode,CardholderName,@CardCompany,CardType,PaymentDate)
SET RentedSpaceID = nullif(@RentedSpaceID, ''), CardExpiryDate = nullif(@CardExpiryDate, ''), CardSecurityCode = nullif(@CardSecurityCode, ''), CardCompany = nullif(@CardCompany, '');

LOAD DATA LOCAL INFILE 'C:/Users/Alex/Desktop/all-your-database/data/Ratings.csv'
INTO TABLE fvc353_2.Ratings
FIELDS TERMINATED BY ','
optionally ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(RatingID,AdIDBeingRated,UserIDRatingAd,Rating);