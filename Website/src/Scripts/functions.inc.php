<?php

include 'settings.php';

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
function get_parkour_spots() {
    $connection = connect(HOSTNAME,USERNAME, DATABASE, PASSWORD);
    $query = "select spot_id, name, address, city, date_format(added_date, '%d-%m-%Y') as date, rating from spot inner join location using(city);";
    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);

    $spots = [];
    while($spot = $q->fetch(PDO::FETCH_ASSOC)) {
        $spots[] = $spot;
    }
    return $spots;
}

/**
 * Displays all Cities
 * @param null $city
 */
function get_city_options($city = null){
    $connection = connect(HOSTNAME,USERNAME, DATABASE, PASSWORD);
    $statement = get_all_cities($connection);
    $count = $statement->rowCount();

    $options = [];

    if ($count > 0) {
        while ($rows = $statement->fetch()){

            $selected = "";

            if ($city === $rows['city']) {
               $selected = 'selected="true"';
            }
            $options[] = "<option $selected value='". $rows['city'] . "'>" .$rows['city'] . "</option>";
        }
    }
    return $options;
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
    $connection = connect(HOSTNAME,USERNAME, DATABASE, PASSWORD);
    $statementSpot = "INSERT INTO spot (name,address,city,rating) VALUES (:name,:address,:city,:rating)";
    $insertSpot = $connection->prepare($statementSpot);

    $result = $insertSpot->execute([
        ':name' => $name,
        ':address' => $location,
        ':city' => $city,
        ':rating' => $rating
    ]);

    if ($result === TRUE) {
        return $connection->lastInsertId();
    }

    return FALSE;
}

/**
 * Get the spot that needs to be updated
 * @param $spot_id
 * @return false|PDOStatement
 */
function get_update_spot($spot_id){
    $query = "select spot_id, name, address, city, added_date, rating from spot inner join location using(city) where spot_id = $spot_id;";
    $connection = connect(HOSTNAME,USERNAME, DATABASE, PASSWORD);
    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    return $q;
}

/**
 * Returns a spot for a given spot_id.
 *
 * @param int $spot_id
 *      ID of the spot
 * @return array|false
 */
function get_spot($spot_id) {
    $connection = connect(HOSTNAME,USERNAME, DATABASE, PASSWORD);
    $statement = $connection->prepare('SELECT spot_id, name, address, city, added_date, rating FROM spot INNER JOIN location USING(city) WHERE spot_id = ?');

    if ($statement->execute([$spot_id])) {
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    return false;
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
    $connection = connect(HOSTNAME,USERNAME, DATABASE, PASSWORD);
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
    $connection = connect(HOSTNAME,USERNAME, DATABASE, PASSWORD);
    $deleteStatement = "delete from spot where spot_id = $spot_id;";
    $deleteSpot = $connection->prepare($deleteStatement);
    return $deleteSpot->execute();
}

/**
 * Class Message
 */
class Message {

    /**
     * @param $message
     */
    public static function setMessage($message) {
        $_SESSION['message'] = $message;
    }

    /**
     * @return mixed
     */
    public static function getMessage(){
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
            return $message;
        }
    }
}

/**
 * @param $spot_id
 */
function remove_directory($spot_id){
    $dir = "src/uploads/$spot_id";
    array_map('unlink', glob("$dir/*.*"));
    if (is_dir($dir)){
        rmdir($dir);
    }
    return true;
}

function edit_spot($spot_id) {


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

        if (($_FILES['my_file']['name']!=="")){
            $dir = "src/uploads/$spot_id";
            if (is_dir($dir)){
                $target_dir = $dir;
            } else {
                mkdir($dir,0777,false, null);
                $target_dir = $dir;
                chmod($target_dir, 0777);
            }

            $file = $_FILES['my_file']['name'];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $_FILES['my_file']['tmp_name'];
            $path_filename_ext = $target_dir."/".$filename.".".$ext;

            if (file_exists($path_filename_ext)) {
                $errors[] = 'Bild existiert bereits bitte wählen sie einen anderen Dateinamen';
            }else{
                move_uploaded_file($temp_name,$path_filename_ext);
            }
        }

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
                Message::setMessage("Änderungen wurden erfolgreich gespeichert");
                header("Location: index.php");
            }
        }
    }



}


function get_spot_form($spot_id = null, $values = [], $errors = []) {

    if (!empty($spot_id)) {
       $spot = get_spot($spot_id);
    }
    elseif (!empty($values)) {
        $spot = $values;
    }

    $name = $spot['name'] ?? '';
    $address = $spot['address'] ?? '';
    $city = $spot['city'] ?? '';
    $rating = $spot['rating'] ?? '';

    $options = implode("\n", get_city_options($city));

    $form = <<<FORM
    <form enctype='multipart/form-data' action='index.php' method='post'>
        <input type='hidden' id='spot_id' name='spot_id' value='$spot_id'><br>
        <input type='hidden' id='action' name='action' value='submit'><br>
        <label for='name'>Spot Name:</label><br>
        <input type='text' id='name' name='name' value='$name'><br>
    
        <label for='address'>Spot Location:</label><br>
        <input type='text' id='address' name='address' value='$address'><br>
    
        <label for='city'>City</label><br>
        <select name='city' id='city' value='$city'>
        $options
        </select><br>
    
        <label for='rating'>Rating 1-10:</label><br>
        <input type='number' id='rating' name='rating' min='1' max='10' value='$rating'><br>
    
        <input type='file' name='my_file'>
        <input type='submit' name='submit' value='Submit'>
    </form>
FORM;

    return $form;
}

function render_spots_table() {

    $spots = get_parkour_spots();

    if (count($spots) > 0) {
        $table = '<table>';
        $table.= '<tr><th>Name</th><th>Location</th><th>City</th><th>Rating</th><th>Added Date</th><th>Delete</th><th>Edit</th><th>Images</th></tr>';
        foreach ($spots as $key => $spot) {
            $table .='<tr>';
            $table .='<td>' . $spot['name'] .'</td>';
            $table .='<td>' . $spot['address'] . '</td>';
            $table .='<td>' . $spot['city'] . '</td>';
            $table .='<td>' . $spot['rating'] . '/10</td>';
            $table .='<td>' . $spot['date'] . '</td>';
            $table .='<td><a href="/index.php?spot_id=' . $spot['spot_id'] . '&action=delete">Delete</a></td>';
            $table .='<td><a href="/index.php?spot_id='. $spot['spot_id'] . '&action=edit">Edit</a></td>';
            $table .='<td><a href="/images.php?spot_id='. $spot['spot_id']. '">Images</a></td>';
            $table .='</tr>';
        }
        $table .='</table>';
        return $table;
    }

    return '';
}

function validate_form_submission($form) {
    $errors = [];

    $name = $form['name'];
    $location = $form['address'];

    $city = $form['city'];
    $rating = $form['rating'];

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

    return $errors;
}