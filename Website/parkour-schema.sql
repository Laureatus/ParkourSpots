DROP DATABASE IF EXISTS parkour;
CREATE DATABASE parkour;

USE parkour;

CREATE TABLE location(
    plz int NOT NULL,
    city varchar(255) NOT NULL,
    PRIMARY KEY (city)
);

CREATE TABLE permission(
   permission_status int NOT NULL AUTO_INCREMENT,
   role_name varchar(255),
   PRIMARY KEY (permission_status)
);

CREATE TABLE users (
   user_id int NOT NULL AUTO_INCREMENT,
   username varchar(20) NOT NULL UNIQUE,
   email varchar(255) NOT NULL UNIQUE,
   password varchar(255) NOT NULL,
   added_time datetime NOT NULL default CURRENT_TIMESTAMP,
   active int NOT NULL default 0,
   permission_status int default 2,
   auth_token int NULL,
   PRIMARY KEY (user_id),
   FOREIGN KEY (permission_status) REFERENCES permission(permission_status)
);

CREATE TABLE spot(
    spot_id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    city varchar(255) NOT NULL,
    name varchar(255) NOT NULL,
    address varchar(255) NOT NULL,
    added_date datetime default CURRENT_TIMESTAMP,
    lng decimal(11,7) NULL,
    lat decimal(11,7) NULL,
    PRIMARY KEY (spot_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id),
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








