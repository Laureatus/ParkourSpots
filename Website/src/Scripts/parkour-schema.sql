DROP DATABASE IF EXISTS parkour;
CREATE DATABASE parkour;

USE parkour;

CREATE TABLE location(
    plz int NOT NULL,
    city varchar(255) NOT NULL,
    PRIMARY KEY (city)
);




CREATE TABLE spot(
    spot_id int NOT NULL AUTO_INCREMENT,
    city varchar(255) NOT NULL,
    name varchar(255) NOT NULL,
    address varchar(255) NOT NULL,
    added_date timestamp default CURRENT_TIMESTAMP,
    lng decimal(11,7) NULL,
    lat decimal(11,7) NULL,
    rating int NOT NULL,
    PRIMARY KEY (spot_id),
    FOREIGN KEY (city) REFERENCES location(city)
);