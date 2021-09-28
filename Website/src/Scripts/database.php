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
include_once 'functions.inc.php';



$connection = connect("database","lorin", "parkour", "db_P@ssw0rd");
$statement = get_parkour_spots($connection);
$count = $statement->rowCount();

if ($count > 0){

    echo "<table>";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Location</th>";
    echo "<th>City</th>";
    echo "<th>Rating</th>";
    echo "<th>Added Date</th>";
    echo "<th>Delete</th>";

    echo "</tr>";
    //LOOP THROUGH ALL QUERY RESULTS
    while ($rows = $statement->fetch()){
        echo "<tr>";
        echo "<td>" . $rows['name'] . "</td>";
        echo "<td>" . $rows['address'] . "</td>";
        echo "<td>" . $rows['city'] . "</td>";
        echo "<td>" . $rows['rating'] . "/10 </td>";
        echo "<td>" . $rows['added_date'] . "</td>";
        echo "<td><a href=\"/index.php?spot_id=". $rows['spot_id']. "\">Delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}




//CLOSE CONNECTION
$connection = null;
