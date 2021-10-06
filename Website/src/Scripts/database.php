<?php

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
    echo "<th>Edit</th>";
    echo "<th>Images</th>";


    echo "</tr>";
    //LOOP THROUGH ALL QUERY RESULTS
    while ($rows = $statement->fetch()){
        echo "<tr>";
        echo "<td>" . $rows['name'] . "</td>";
        echo "<td>" . $rows['address'] . "</td>";
        echo "<td>" . $rows['city'] . "</td>";
        echo "<td>" . $rows['rating'] . "/10 </td>";
        echo "<td>" . $rows['date'] . "</td>";
        echo "<td><a href=\"/index.php?spot_id=". $rows['spot_id']. "\">Delete</a></td>";
        echo "<td><a href=\"/edit.php?spot_id=". $rows['spot_id']. "\">Edit</a></td>";
        echo "<td><a href=\"/images.php?spot_id=". $rows['spot_id']. "\">Images</a></td>";

        echo "</tr>";
    }
    echo "</table>";
}




//CLOSE CONNECTION
$connection = null;
