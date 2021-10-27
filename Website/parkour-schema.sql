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
    added_date datetime default CURRENT_TIMESTAMP,
    lng decimal(11,7) NULL,
    lat decimal(11,7) NULL,
    PRIMARY KEY (spot_id),
    FOREIGN KEY (city) REFERENCES location(city)
);

CREATE TABLE images(
    image_id int NOT NULL AUTO_INCREMENT,
    spot_id int NOT NULL,
    path varchar(255),
    name varchar(255),
    size varchar(255),
    PRIMARY KEY (image_id),
    FOREIGN KEY (spot_id) REFERENCES spot(spot_id)
);

CREATE TABLE review(
    description_id int NOT NULL AUTO_INCREMENT,
    spot_id int NOT NULL,
    comment varchar(500) NOT NULL,
    rating int(2) NOT NULL,
    PRIMARY KEY (description_id),
    FOREIGN KEY (spot_id) REFERENCES spot(spot_id)
);



