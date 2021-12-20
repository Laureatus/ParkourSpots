<?php

/**
 * @file
 * Important Functions for the Application.
 */

include 'settings.php';


use Parkour\Message;
use Parkour\Connection;

/**
 * Get all cities.
 *
 * @return array
 *   List of all cities in an indexed array.
 *
 * @todo CityRepository & funktion löschen
 */
function get_all_cities() {
  $connection = Connection::connect();

  $query = "SELECT city FROM location;";
  $statement = $connection->query($query);

  if ($statement != FALSE) {
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $options = [];
    if ($statement->rowCount() > 0) {
      while ($rows = $statement->fetch()) {
        $options[] = $rows['city'];
      }
    }
  }


  return $options;
}

/**
 * Deletes a spot from the Database.
 *
 * @todo In Spot Objekt einfügen ($spot->delete()) inkl. remove_directory
 *
 * @param int $spot_id
 *   The ID of a spot.
 *
 * @return bool
 *   Return TRUE|FALSE.
 */
function delete_spot($spot_id) {
  $connection = Connection::connect();
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
 * Delete a Image directory.
 *
 * @param int $spot_id
 *   The ID of a spot.
 *
 * @return bool
 *   Return TRUE|FALSE
 */
function remove_directory($spot_id) {
  $dir = "src/uploads/$spot_id";
  array_map('unlink', glob("$dir/*.*"));
  if (is_dir($dir)) {
    rmdir($dir);
  }
  return TRUE;
}

/**
 * Validate the Form Input fields.
 *
 * @param mixed $form
 *   All the Form input data.
 *
 * @return array
 *   Return ERRORS
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

  Message::setMessage(implode("<br>", $errors));
  return $errors;

}

/**
 * Validate registration.
 *
 * @param mixed $form
 *   Registration data.
 *
 * @return array
 *   Return ERRORS
 */
function validate_registration($form) {
  $username = $form['username'];
  $email = $form['email'];
  $password = $form['password'];
  $errors = [];

  $emailQuery = "select email from users;";
  $connection = Connection::connect();
  $q = $connection->query($emailQuery);
  if ($q != FALSE) {
    $q->setFetchMode(PDO::FETCH_ASSOC);
  }


  if (empty($username)) {
    $errors[] = 'username darf nicht leer sein';
  }

  if (empty($email)) {
    $errors[] = 'Email darf nicht leer sein';
  }
  elseif ($q != FALSE) {
    while ($user = $q->fetch(PDO::FETCH_COLUMN)) {
      if ($email == $user) {
        $error[] = "Email wird bereits verwendet.";
      }
    }
  }

  if (empty($password)) {
    $errors[] = 'Passwort darf nicht leer sein';
  }
  return $errors;
}

/**
 * Send Veryfication Email.
 *
 * @param mixed $form
 *   Form Data.
 */
function mailing($form) {
  $email = $form['email'];
  $username = $form['username'];
  $token = "";
  $emailQuery = "select auth_token from users where username = '$username';";
  $connection = Connection::connect();
  $q = $connection->query($emailQuery);
  if ($q != FALSE) {
    $q->setFetchMode(PDO::FETCH_ASSOC);
    while ($auth_token = $q->fetch(PDO::FETCH_COLUMN)) {
      $token = $auth_token;
    }
  }


  $to = $email;
  $subject = "Authenthifiziere deinen account";

  $message = "

              Hallo $username

              Bitte schliesse deine Registrierung für parkour.lndo.site ab inden du auf den untenstehenden Link klickst

              https://parkour.lndo.site/index.php?action=verify&username=" . $username . "&auth_token=" . $token . "

              ";

  // It is mandatory to set the content-type when sending HTML email.
  $headers = "MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n";

  // More headers. From is required, rest other headers are optional.
  $headers .= 'From: <noreply@parkour.com>' . "\r\n";

  mail($to, $subject, $message, $headers);
}
