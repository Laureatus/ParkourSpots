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
function get_all_cities($connection){
    $query = "select city from location;";

    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    return $q;
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
    $query = "select spot_id, name, address, city, added_date, rating from spot inner join location using(city);";

    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    return $q;
}

/**
 * @todo Comment me! :-)
 */
function displayCity(){
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
    $statement = get_all_cities($connection);
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

function delete_spot($spot_id) {
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
    $deleteStatement = "delete from spot where spot_id = $spot_id;";
    $deleteSpot = $connection->prepare($deleteStatement);
    return $deleteSpot->execute();
}