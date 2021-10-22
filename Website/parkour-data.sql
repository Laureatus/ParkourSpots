source Website/src/Scripts/parkour-schema.sql;


LOAD DATA LOCAL INFILE 'Website/src/Scripts/plz_light.csv'
INTO TABLE location
FIELDS TERMINATED BY ';'
ENCLOSED BY ';'
LINES TERMINATED BY ';'
