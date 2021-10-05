<?php
echo "<head>";
echo "<link rel='stylesheet' type='text/css' href='src/Styles/styles.css'>";
echo "</head>";
include_once 'src/Scripts/functions.inc.php';
include 'src/Scripts/navbar.php';

//Get spot_id using GET method
$spot_id = $_GET['spot_id'] ?? $_POST['spot_id'];


$q = get_update_spot($spot_id);
//Fetch Data from $q
while ($row = $q->fetch()) {
    $name = $row['name'];
    $location = $row['address'];
    $city = $row['city'];
    $rating = $row['rating'];
}




if (isset($_POST['submit'])) {
    $errors = [];
    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['address']);
    $array = array_values($_POST);
    $city = $array[3];
    $rating = htmlspecialchars($_POST['rating']);
    $spot_id = $_POST['spot_id'];

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
        if (update_spot($spot_id,$name, $location, $city, $rating)) {
            //alert("Änderungen wurden erfolgreich gespeichert");
            Message::setMessage("Änderungen wurden erfolgreich gespeichert");
            header("Location: index.php");
        }
    }
}

echo "<section class='container' >";
echo "    <div class='col-6'>";
echo "        <article class='leistungs-box leistung-box-empfohlen'>";
echo "            <h1>Spot Editieren</h1>";
echo "            <div>";
echo "<form action='edit.php' method='post'>";
echo "<input type='hidden' id='spot_id' name='spot_id' value='$spot_id'><br>";
//Name
echo  "<label for='name'>Spot Name:</label><br>";
echo "<input type='text' id='name' name='name' value='$name'><br>";
//Location
echo  "<label for='address'>Spot Location:</label><br>";
echo "<input type='text' id='address' name='address' value='$location'><br>";
//City
echo  "<label for='city'>City</label><br>";
echo "<select name='city' id='city' value='$city'>";
displayCity($city);
echo "</select><br>";
//Rating
echo  "<label for='rating'>Rating 1-10:</label><br>";
echo "<input type='number' id='rating' name='rating' min='1' max='10' value='$rating'><br>";
//Submit
echo "<input type='submit' name='submit' value='Submit'>";
echo "</form>";
echo "            </div>";
echo "        </article>";
echo "    </div>";
echo "</section>";
