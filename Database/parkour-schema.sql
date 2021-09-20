DROP DATABASE IF EXISTS parkour;
CREATE DATABASE parkour;

USE parkour;

CREATE TABLE country(
    country_id int NOT NULL AUTO_INCREMENT,
    country varchar(255) NOT NULL,
    PRIMARY KEY (country_id)
);

CREATE TABLE city(
    city_id int NOT NULL AUTO_INCREMENT,
    country_id int NOT NULL,
    city varchar(255) NOT NULL,
    PRIMARY KEY (city_id),
    FOREIGN KEY (country_id) REFERENCES country(country_id)
);




CREATE TABLE spot(
    spot_id int NOT NULL AUTO_INCREMENT,
    city_id int NOT NULL,
    name varchar(255) NOT NULL,
    location varchar(255) NOT NULL,
    added_date timestamp default CURRENT_TIMESTAMP,
    lng decimal(11,7) NOT NULL,
    lat decimal(11,7) NOT NULL,
    PRIMARY KEY (spot_id),
    FOREIGN KEY (city_id) REFERENCES city(city_id)
);
