<?php

include 'settings.php';
include 'src/Scripts/head.php';

use Parkour\SpotRepository;
use Parkour\Message;

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
    $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
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
    $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
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
 * Deletes a spot from the Database
 *
 * @param $spot_id
 * @return bool
 */
function delete_spot($spot_id) {
    $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
    $delete_image_fk = "delete from images where spot_id = $spot_id;";
    $delete_description_fk = "delete from description where spot_id = $spot_id;";
    $delete_pk = "delete from spot where spot_id = $spot_id;";
    $prepare_image_fk = $connection->prepare($delete_image_fk);
    $prepare_description_fk = $connection->prepare($delete_description_fk);
    $prepare_image_fk->execute();
    $prepare_description_fk->execute();
    $prepare_pk = $connection->prepare($delete_pk);
    return $prepare_pk->execute();
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

function get_spot_form($spot_id = null, $values = [], $errors = []) {

  $repository = new SpotRepository();
    if (!empty($spot_id)) {
       $spot = $repository->getSpot($spot_id);
    }
    elseif (!empty($values)) {
        $spot = $values;
    }

    $options = implode("\n", get_city_options($spot->getCity()));

    $form = '<form enctype="multipart/form-data" action="index.php" method="post">';
    $form.= sprintf('<input type="hidden" id="spot_id" name="spot_id" value="%s"><br>', $spot->getSpotId());
    $form.= '<input type="hidden" id="action" name="action" value="submit"><br>';

    $form.= '<label for="name">Spot Name:</label><br>';
    $form.= sprintf('<input type="text" id="name" name="name" value="%s"><br>', $spot->getName());

    $form.= '<label for="address">Spot Location:</label><br>';
    $form.= sprintf('<input type="text" id="address" name="address" value="%s"><br>', $spot->getAddress());

    $form.= '<label for="city">City</label><br>';
    $form.= '<select name="city" id="city">';
    $form.= $options;
    $form.= '</select><br>';

    $form.= '<label for="rating">Rating 1-10:</label><br>';
    $form.= sprintf('<input type="number" id="rating" name="rating" min="1" max="10" value="%d"><br>', $spot->getRating());

    $form.= '<input type="file" name="my_file">';
    $form.= '<input type="submit" value="Submit">';
    $form.= '</form>';

    return $form;
}

function render_spots_table() {

  $repository = new SpotRepository();
  /** @var \Parkour\Spot[] $spots */
  $spots = $repository->getAllSpots();

    if (count($spots) > 0) {
        $table = '<table>';
        $table.= '<tr><th>Name</th><th>Location</th><th>City</th><th>Rating</th><th>Added Date</th><th>Delete</th><th>Edit</th><th>Images</th></tr>';
        foreach ($spots as $key => $spot) {
            $table .='<tr>';
            $table .='<td><a href="/index.php?spot_id=' . $spot->getSpotId() . '&action=detail_view">' . $spot->getName() .'</a></td>';
            $table .='<td>' . $spot->getAddress() . '</td>';
            $table .='<td>' . $spot->getCity() . '</td>';
            $table .='<td>' . $spot->getRating() . '/10</td>';
            $table .='<td>' . $spot->getAddedDate() . '</td>';
            $table .='<td><a href="/index.php?spot_id=' . $spot->getSpotId() . '&action=delete">Delete</a></td>';
            $table .='<td><a href="/index.php?spot_id='. $spot->getSpotId() . '&action=edit">Edit</a></td>';
            $table .='<td><a href="/index.php?spot_id='. $spot->getSpotId(). '&action=images">Images</a></td>';
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

    Message::setMessage(implode("<br>",$errors));
    return $errors;

}

function render_images($spot_id) {

    // SELECT * FROM images where spot_id=$spot_id;

    $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
    $query = "SELECT * FROM images WHERE spot_id=".$spot_id."." ;
    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);

    $directory = TARGETDIR.$spot_id;

    if (!is_dir($directory)) {
        return "Couldn't find enclosing image folder:  " . $directory;
    }

    $handle = opendir($directory);
    if (!$handle) {
        return "Couldn't open $directory for reading.";
    }

    $images = '';
    // Loop über SQL-Result -> id, path, name
    foreach ($q as $key => $image) {
        $images.= "<img src=\"".TARGETDIR.$image['path']."\"><a href=\"index.php?action=delete_image&image_id=".$image['image_id']."&spot_id=$spot_id\">Delete</a>";
    }
    closedir($handle);

    return $images;
}


// $file = $_FILES['my_file']

function upload_image($spot_id, $image) {
    if ($image['name']!=="") {
        $dir = TARGETDIR.$spot_id;
        if (is_dir($dir)) {
            $target_dir = $dir;
        } else {
            mkdir($dir,0777,false, null);
            $target_dir = $dir;
            chmod($target_dir, 0777);
        }
        $file = $image['name'];
        $path = pathinfo($file);
        $filename = $path['filename'];
        $ext = $path['extension'];
        $temp_name = $image['tmp_name'];
        $path_filename_ext = $target_dir."/".$filename.".".$ext;
        $db_path = "$spot_id/$filename.$ext";

        if (file_exists($path_filename_ext)) {
            throw new FileExistsException('Bild existiert bereits bitte wählen sie einen anderen Dateinamen');
        }
        else {
            $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
            $statementSpot = "INSERT INTO images (path, spot_id) VALUES (:db_path, :spot_id)";
            $insertSpot = $connection->prepare($statementSpot);

            $insertSpot->execute([
                ':db_path' => $db_path,
                ':spot_id' => $spot_id,
            ]);
           return move_uploaded_file($temp_name,$path_filename_ext);
        }


        return $errors;
    }

}

function delete_image($image_id){
    $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
    $query = "SELECT * FROM images WHERE image_id=".$image_id.".";
    $results = $connection->query($query);
    $results->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($results as $key => $result) {
        $filepath = TARGETDIR.$result['path'];
        if (is_file($filepath)) {
            unlink($filepath);
        }
        $connection->query('DELETE FROM images WHERE image_id=' . $image_id);
    }
}

function check_dir($spot_id){
    $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
    $sql = "select count(*) from images where spot_id = $spot_id;";
    $res = $connection->query($sql);
    $count = $res->fetchColumn();
    return $count;
}



function show_detail_view($spot_id){
    $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
    $query = "SELECT * FROM spot WHERE spot_id=".$spot_id."." ;
    $spot = $connection->query($query);
    $spot->setFetchMode(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM description WHERE spot_id=".$spot_id."." ;
    $description = $connection->query($query);
    $description->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($spot as $key => $spot) {
            $table = '<table>';
            $table.= '<tr><th>Name</th><th>Location</th><th>City</th><th>Rating</th><th>Added Date</th><th>Add new Description</th></tr>';
            $table .='<tr>';
            $table .='<td>' . $spot['name'] .'</a></td>';
            $table .='<td>' . $spot['address'] . '</td>';
            $table .='<td>' . $spot['city'] . '</td>';
            $table .='<td>' . $spot['rating'] . '/10</td>';
            $table .='<td>' . $spot['added_date'] . '</td>';
            $table .='<td rowspan="4" colspan="0.5">' . get_description_form($spot_id). '</td>';
            $table .='</tr>';
            $table.= '<tr><th colspan="5">Description</th></tr>';
            foreach ($description as $value => $description) {
                $table .= '<tr><td colspan="4">' . $description['description'] . '</td>
                <td><a href="/index.php?spot_id='.$spot['spot_id'].'&description_id=' . $description['description_id'] . '&action=delete_description">Delete</a></td></tr>';
            }
            $table .='</table>';
        }
        $table .=render_images($spot_id);
        return $table;
}

function get_description_form($spot_id = null, $values = [], $errors = []) {

    if (!empty($spot_id)) {
        $spot = get_spot($spot_id);
    }
    elseif (!empty($values)) {
        $spot = $values;
    }

    $description = $spot['description'] ?? '';


    $form = <<<FORM
    <form enctype='multipart/form-data' action='index.php' method='post'>
        <input type='hidden' id='action' name='action' value='submit_description'><br>
        <input type='hidden' id='spot_id' name='spot_id' value='$spot_id'><br>
        <input type='hidden' id='description_id' name='description_id' value='description_id'><br>
        <label for='name'>Description:</label><br>
        <textarea style="resize: vertical; height: 250px; width: 300px; word-break: break-word;" maxlength="500" type='text' id='name' name='description' value='$description'></textarea>
        <input type='submit' name='add' value='Submit'>
    </form>
FORM;

    return $form;
}

function insert_description($spot_id, $description){
        $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
        $statementSpot = "INSERT INTO description (spot_id, description) VALUES (:spot_id, :description)";
        $insertSpot = $connection->prepare($statementSpot);
        $result = $insertSpot->execute([
            ':spot_id' => $spot_id,
            ':description' => $description
        ]);
        if ($result === TRUE) {
            return $connection->lastInsertId();
        }

        return FALSE;
}

function delete_description($description_id){
    $connection = connect(DB_HOSTNAME,DB_USERNAME, DB_NAME, DB_PASSWORD);
    $query = "delete from description where description_id = $description_id;";
    $prepare = $connection->prepare($query);
    return $prepare->execute();
}

