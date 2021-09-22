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
    $query = "select country,country_id from country;";

    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    return $q;
}





function displayCountrys(){
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
    $statement = get_parkour_spots($connection);
    $count = $statement->rowCount();
    if ($count > 0) {
        while ($rows = $statement->fetch()){
            echo "<option value= ". $rows['country_id'] . ">" .$rows['country'] . "</option>";
        }
    }
}

    echo "<form action='/src/Scripts/newSpotValidate.php' method='post'>";
        //Name
        echo  "<label for='name'>Spot Name:</label><br>";
        echo "<input type='text' id='name' name='name'><br>";
        //Location
        echo  "<label for='location'>Spot Location:</label><br>";
        echo "<input type='text' id='location' name='location'><br>";
        //City
        echo  "<label for='city'>City</label><br>";
        echo "<input type='text' id='city' name='city'><br>";
        //Country
        echo  "<label for='country'>Country</label><br>";
        echo "<select name='country' id='country'>";
                displayCountrys();
        echo "</select><br>";
        //Rating
        echo  "<label for='rating'>Rating 1-10:</label><br>";
        echo "<input type='number' id='rating' name='rating' min='1' max='10'><br>";
        //Submit
        echo "<input type='submit' value='Submit'>";
    echo "</form>";




//CLOSE CONNECTION
$connection = null;

