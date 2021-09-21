<?php
/**
 * Referenzen & Parameter optional und Pflicht parameter static global type hint
 * PHPDOC schreiben
 * Heredoc
 * Klassen & Objekte
 *
 * public protected private
 * klassen -> abstract interface final
 * extend implement
 * static methods
 * magic methods __construct __destruct __get __set
 * __sleep __serialize __unserialize __toString()
 * namespaces autoload
 * psr standards
 * ternärer Operator ?? ?:
 * @todo traits

 * Sortierung nach description
 * @todo Einbauen limitierung
 *
 * Was sind design pattern
 * gruppen erzeugung/verhalten
 * singleton pattern
 * factory pattern umsetzen und testen
 *
 **/

// string
// int
// bool
// float
// null
// array
// objects
//Modulo Operator



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
    $query = "select name, location, city, country, added_date, rating from spot inner join city using(city_id) inner join country using(country_id);";

    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    return $q;

}


$connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
$statement = get_parkour_spots($connection);
$count = $statement->rowCount();

if ($count > 0){

    echo "<table>";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Location</th>";
    echo "<th>City</th>";
    echo "<th>Country</th>";
    echo "<th>Rating</th>";
    echo "<th>Added Date</th>";
    echo "</tr>";
    //LOOP THROUGH ALL QUERY RESULTS
    while ($rows = $statement->fetch()){
        echo "<tr>";
        echo "<td>" . $rows['name'] . "</td>";
        echo "<td>" . $rows['location'] . "</td>";
        echo "<td>" . $rows['city'] . "</td>";
        echo "<td>" . $rows['country'] . "</td>";
        echo "<td>" . $rows['rating'] . "/10 </td>";
        echo "<td>" . $rows['added_date'] . "</td>";
        echo "</tr>";

    }
    echo "</table>";
}


//CLOSE CONNECTION
$connection = null;
