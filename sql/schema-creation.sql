DROP TABLE IF EXISTS categoryProduct;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS orderProduct;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS checkout;
DROP TABLE IF EXISTS `order`;
DROP TABLE IF EXISTS storeLocation;
DROP TABLE IF EXISTS location;
DROP TABLE IF EXISTS store;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
	userId INT UNSIGNED AUTO_INCREMENT,
	email VARCHAR(100) UNIQUE NOT NULL,
	hash VARCHAR(128) UNIQUE NOT NULL,
	salt VARCHAR(32) UNIQUE NOT NULL,
	activation VARCHAR(16) UNIQUE NOT NULL,
	PRIMARY KEY(userId)
);

CREATE TABLE profile (
	profileId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	firstName VARCHAR(45),
	lastName VARCHAR(45),
	phone VARCHAR(20),
	profileType CHAR(1),
	customerToken VARCHAR(50),
	imagePath VARCHAR(255),
	userId INT UNSIGNED NOT NULL,
	INDEX(userId),
	FOREIGN KEY(userId) REFERENCES user(userId),
	PRIMARY KEY(profileId)
);

CREATE TABLE store (
	storeId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	creationDate DATETIME NOT NULL,
	storeName VARCHAR(100),
	imagePath VARCHAR(255),
	storeDescription TEXT,
	profileId INT UNSIGNED NOT NULL,
	INDEX(profileId),
	FOREIGN KEY(profileId) REFERENCES profile(profileId),
	PRIMARY KEY(storeId)
);

CREATE TABLE location (
	locationId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	locationName VARCHAR(100),
	country CHAR(2),
	state CHAR(2),
	city VARCHAR(100),
	zipCode CHAR(10),
	address1 VARCHAR(150),
	address2 VARCHAR(150),
	PRIMARY KEY(locationId)
);

CREATE TABLE storeLocation(
	locationId INT UNSIGNED NOT NULL,
	storeId INT UNSIGNED NOT NULL,
	INDEX(locationId),
	INDEX(storeId),
	FOREIGN KEY(locationId) REFERENCES location(locationId),
	FOREIGN KEY(storeId) REFERENCES store(storeId),
	PRIMARY KEY(locationId, storeId)
);

CREATE TABLE `order`(
	orderId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	orderDate DATETIME NOT NULL,
	profileId INT UNSIGNED NOT NULL,
	INDEX(profileId),
	FOREIGN KEY(profileId) REFERENCES profile(profileId),
	PRIMARY KEY(orderId)
);

CREATE TABLE checkout (
	checkoutId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	orderId INT UNSIGNED NOT NULL,
	checkoutDate DATETIME NOT NULL,
	finalPrice DECIMAL(9, 2) NOT NULL,
	INDEX(orderId),
	FOREIGN KEY(orderId) REFERENCES `order`(orderId),
	PRIMARY KEY(checkoutId)
);

CREATE TABLE product (
	productId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	productName VARCHAR(45) NOT NULL,
	productPrice DECIMAL(7,2) NOT NULL,
	productDescription TEXT,
	productPriceType CHAR(1) NOT NULL, -- w(weight) or u(unit)
	productWeight DECIMAL(8, 4),
	imagePath VARCHAR(255),
	stockLimit INT UNSIGNED,
	storeId INT UNSIGNED NOT NULL,
	INDEX(storeId),
	FOREIGN KEY(storeId) REFERENCES store(storeId),
	PRIMARY KEY(productId)
);

CREATE TABLE orderProduct (
	productQuantity INT UNSIGNED NOT NULL,
	orderId INT UNSIGNED NOT NULL,
	productId INT UNSIGNED NOT NULL,
	locationId INT UNSIGNED NOT NULL,
	INDEX(orderId),
	INDEX(productId),
	INDEX(locationId),
	FOREIGN KEY(orderId) REFERENCES `order`(orderId),
	FOREIGN KEY(productId) REFERENCES product(productId),
	FOREIGN KEY(locationId) REFERENCES location(locationId),
	PRIMARY KEY(orderId, productId)
);

CREATE TABLE category (
	categoryId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	categoryName VARCHAR(20) NOT NULL,
	PRIMARY KEY(categoryId)
);

CREATE TABLE categoryProduct (
	categoryId INT UNSIGNED NOT NULL,
	productId INT UNSIGNED NOT NULL,
	INDEX(categoryId),
	INDEX(productId),
	FOREIGN KEY(categoryId) REFERENCES category(categoryId),
	FOREIGN KEY(productId) REFERENCES product(productId),
	PRIMARY KEY(categoryId, productId)
);