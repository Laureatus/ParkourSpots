LOAD DATA LOCAL INFILE 'src/Scripts/plz_light.csv'
INTO TABLE location
FIELDS TERMINATED BY ';'
ENCLOSED BY ';'
LINES TERMINATED BY ';';

INSERT INTO permission (role_name) values ('Admin');
INSERT INTO permission (role_name) values ('User');
