<?php

include 'settings.php';


use Parkour\Image;
use Parkour\SpotRepository;
use Parkour\Message;
use Parkour\connection;
use Parkour\ReviewRepository;


/**
 * Get all cities.
 *
 * @return array
 *   List of all cities in an indexed array.
 *
 * @todo CityRepository & funktion löschen
 */
function get_all_cities() : array {
  $connection = connection::connect();

  $query = "SELECT city FROM location;";
  $statement = $connection->query($query);
  $statement->setFetchMode(PDO::FETCH_ASSOC);

  $options = [];

  if ($statement->rowCount() > 0) {
    while ($rows = $statement->fetch()){
      $options[] = $rows['city'];
    }
  }

  return $options;
}

/**
 * Deletes a spot from the Database
 *
 * @todo In Spot Objekt einfügen ($spot->delete()) inkl. remove_directory
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

/**
 * @todo Comment me :-)
 *
 * @param $form
 *
 * @return array
 */
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

function validate_registration($form) {
  $username = $form['username'];
  $email = $form['email'];
  $password = $form['password'];

  $errors = [];

  if(empty($username)) {
    $errors[] = 'username darf nicht leer sein';
  }

  if(empty($email)) {
    $errors[] = 'Email darf nicht leer sein';
  }

  if(empty($password)) {
    $errors[] = 'Passwort darf nicht leer sein';
  }
  return $errors;

}
