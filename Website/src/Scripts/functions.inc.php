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
 * Displays all Cities
 * @param null $city
 */
function displayCity($city = null){
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
    $statement = get_all_cities($connection);
    $count = $statement->rowCount();
    if ($count > 0) {
        while ($rows = $statement->fetch()){

            $selected = "";

            if ($city === $rows['city']) {
               $selected = 'selected="true"';
            }
            echo "<option $selected value='". $rows['city'] . "'>" .$rows['city'] . "</option>";

        }
    }
}

/**
 * Adds new Spot to database
 *
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

/**
 * Get the spot that needs to be updated
 * @param $spot_id
 * @return false|PDOStatement
 */
function get_update_spot($spot_id){
    $query = "select spot_id, name, address, city, added_date, rating from spot inner join location using(city) where spot_id = $spot_id;";
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    return $q;
}

/**
 * Updates the spot Information
 * @param $spot_id
 * @param $name
 * @param $address
 * @param $city
 * @param $rating
 * @return bool
 */
function update_spot($spot_id, $name, $address, $city, $rating){
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
    $editStatement = "update spot set name =  '$name', address = '$address', city = '$city', rating = '$rating' where spot_id = '$spot_id'";
    $editSpot = $connection->prepare($editStatement);
    return $editSpot->execute();
}

/**
 * Deletes a spot from the Database
 *
 * @param $spot_id
 * @return bool
 */
function delete_spot($spot_id) {
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
    $deleteStatement = "delete from spot where spot_id = $spot_id;";
    $deleteSpot = $connection->prepare($deleteStatement);
    return $deleteSpot->execute();
}