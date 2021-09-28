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
function get_parkour_spots($connection){
    $query = "select city from location;";

    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    return $q;
}


/**
 * @todo Comment me! :-)
 */
function displayCity(){
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
    $statement = get_parkour_spots($connection);
    $count = $statement->rowCount();
    if ($count > 0) {
        while ($rows = $statement->fetch()){
            echo "<option value= ". $rows['city'] . ">" .$rows['city'] . "</option>";
        }
    }
}

/**
 * @param $name
 * @param $location
 * @param $city
 * @param $rating
 * @return bool
 */
function insert_spot($name, $location, $city, $rating) {
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
    $statementSpot = "insert into spot (name,address,city,rating) VALUES (:name,:address,:city,:rating)";
    $insertSpot = $connection->prepare($statementSpot);

    return $insertSpot->execute([
        ':name' => $name,
        ':address' => $location,
        ':city' => $city,
        ':rating' => $rating
    ]);
}