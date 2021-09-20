source Database/parkour-schema.sql;

INSERT INTO country (country)
VALUES ('Schweiz');

INSERT INTO city (city,country_id)
VALUES ('Zürich','1');

INSERT INTO spot (city_id,name,location,lng,lat)
VALUES ('1','Spielplatz','Konradstrasse 73','8.532927', '47.381973');
