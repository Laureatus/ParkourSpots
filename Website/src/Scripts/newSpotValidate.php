<?php

/**
 * Connects to a mysql database.
 *
 * @param string $hostname
 *   The hostname to connect.
 * @param string $username
 * @param string $database
 * @param string $password
 *
 * @return PDO
 *
 */

function connect(string $hostname, string $username, string $database, string $password) : PDO {
    return new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
}

/**
 * Gets Information from the Database
 *
 * @param PDO $connection
 *      The database to connect to
 * @param $query
 *      The MySQL Query to get the spots Information
 *
 * @return PDOStatement|false
 */


$name = $_POST['name'];
$location = $_POST['location'];
$rating = $_POST['rating'];
$city = $_POST['city'];
$array = array_values($_POST);
$country = $array[3];
$connection = connect("database","lorin", "parkour", "db_P@ssw0rd");




$statementSpot = "insert into spot (name,location,rating) VALUES (:name,:location,:rating)";
$statementCity = "insert into city (city,country_id) VALUES (:city,:country_id)";
$insertSpot = $connection->prepare($statementSpot);
$insertCity = $connection->prepare($statementCity);

$insertSpot->execute([
    ':name' => $name,
    ':location' => $location,
    ':rating' => $rating
]);

$insertCity->execute([
    ':city' => $city,
    ':country_id' => $country
]);