<?php

include 'settings.php';
include 'src/Scripts/head.php';

use Parkour\Image;
use Parkour\SpotRepository;
use Parkour\Message;
use Parkour\connection;
use Parkour\ReviewRepository;

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
    $connection = connection::connect();
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
    $connection = connection::connect();
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
    $connection = connection::connect();
    $delete_image_fk = "delete from images where spot_id = $spot_id;";
    $delete_review_fk = "delete from review where spot_id = $spot_id;";
    $delete_pk = "delete from spot where spot_id = $spot_id;";
    $prepare_image_fk = $connection->prepare($delete_image_fk);
    $prepare_description_fk = $connection->prepare($delete_review_fk);
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

  $spot = new \Parkour\Spot($_REQUEST);

  $repository = new SpotRepository();
    if (!empty($spot_id)) {
       $spot = $repository->getSpot($spot_id);
    }
    elseif (!empty($values)) {
        $spot = $values;
    }


    $options = implode("\n", get_city_options($spot->getCity()));

    $form = '<form enctype="multipart/form-data" action="index.php" method="post" id="">';
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
    $form.= '<br><input type="file" name="my_file">';
    $form.= '<input type="submit" value="Submit">';
    $form.= '</form>';

    return $form;
}

function render_spots_table() {

  $repository = new SpotRepository();
  /** @var \Parkour\Spot[] $spots */
  $spots = $repository->getAllSpots();
  $review = new ReviewRepository();

    if (count($spots) > 0) {
        $table = '<table>';
        $table.= '<tr><th>Name</th><th>Location</th><th>City</th><th>Rating</th><th>Added Date</th><th>Delete</th><th>Edit</th><th>Images</th></tr>';
        foreach ($spots as $key => $spot) {
            $table .='<tr>';
            $table .='<td><a href="/index.php?spot_id=' . $spot->getSpotId() . '&action=detail_view">' . $spot->getName() .'</a></td>';
            $table .='<td>' . $spot->getAddress() . '</td>';
            $table .='<td>' . $spot->getCity() . '</td>';
            $table .='<td>' .$review->SelectRatingAvg($spot->getSpotId())  .'/10'. '</td>';
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

    if (empty($name)) {
       $errors[] = 'Spot Name darf nicht leer sein';
    }

    if (empty($location)) {
        $errors[] = 'Addresse darf nicht leer sein';
    }

    if (empty($city)) {
        $errors[] = 'Stadt darf nicht leer sein';
    }

    Message::setMessage(implode("<br>",$errors));
    return $errors;

}





function show_detail_view($spot_id){
  $spot = SpotRepository::getSpot($spot_id);

    $table = '<table>';
    $table.= '<tr><th>Name</th><th>Location</th><th>City</th><th>Rating</th><th>Added Date</th><th>Add new Description</th></tr>';
    $table .='<tr>';
    $table .='<td>' . $spot->getName() .'</a></td>';
    $table .='<td>' . $spot->getAddress() . '</td>';
    $table .='<td>' . $spot->getCity() . '</td>';
    $table .='<td>' . $spot->getRating() . '/10</td>';
    $table .='<td>' . $spot->getAddedDate() . '</td>';
    $table .='<td rowspan="4" colspan="0.5">' . \Parkour\ReviewForm::render($spot_id). '</td>';
    $table .='</tr>';
    $table.= '<tr><th colspan="5">Description</th></tr>';
    foreach ($spot->getDescriptions() as $key => $description) {
      $table .= '<tr><td colspan="4">' . $description->getDescription() . '</td>
                <td><a href="/index.php?spot_id='.$spot->getSpotId().'&description_id=' . $description->getDescriptionId() . '&action=delete_description">Delete</a></td></tr>';
    }
    $table .='</table>';
  $image = new Image();
  $table .=$image->render_images($spot_id);
  return $table;
}

