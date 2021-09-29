<?php

include 'functions.inc.php';

$name = "";
$location = "";
$array = "";
$city = "";
$rating = "";

if (isset($_POST['submit'])) {
    $errors = [];

    $name = $_POST['name'];
    $location = $_POST['address'];
    $array = array_values($_POST);
    $city = $array[2];
    $rating = $_POST['rating'];
    $connection = connect("database","lorin", "parkour", "db_P@ssw0rd");

    if (empty($name)) {
        $errors[] = 'Spot Name darf nicht leer sein';
    }

    if (empty($location)) {
        $errors[] = 'Addresse darf nicht leer sein';
    }

    if (empty($city)) {
        $errors[] = 'Stadt darf nicht leer sein';
    }

    if (empty($rating)) {
        $errors[] = 'Bewertung darf nicht leer sein';
    }

    if (count($errors) > 0) {
        echo '<h2>Ihr Formular ist nicht vollständig ausgefüllt</h2>';
        echo '<p>Füllen Sie auch die folgenden Felder aus:<br>';
        echo implode('<br>', $errors);
        echo '</p>';
    } else {
        if (insert_spot($name, $location, $city, $rating)) {
            echo "Neuer Spot wurde hinzugefügt";
            // Redirect
            header('Location: ../../index.php');
        }
    }
}

echo "<form action='/src/Scripts/newSpot.php' method='post'>";
    //Name
    echo  "<label for='name'>Spot Name:</label><br>";
    echo "<input type='text' id='name' name='name' value='$name'><br>";
    //Location
    echo  "<label for='address'>Spot Location:</label><br>";
    echo "<input type='text' id='address' name='address' value='$location'><br>";
    //City
    echo  "<label for='city'>City</label><br>";
    echo "<select name='city' id='city' value='$city'>";
            displayCity();
    echo "</select><br>";
    //Rating
    echo  "<label for='rating'>Rating 1-10:</label><br>";
    echo "<input type='number' id='rating' name='rating' min='1' max='10' value='$rating'><br>";
    //Submit
    echo "<input type='submit' name='submit' value='Submit'>";
echo "</form>";
