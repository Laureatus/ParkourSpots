<?php

/**
 * @file
 * Index file.
 */

include 'autoload.php';
require_once __DIR__ . '/bootstrap.php';
include_once 'src/Scripts/functions.inc.php';
include_once 'src/Scripts/settings.php';


use Parkour\FileExistsException;
use Parkour\Image;
use Parkour\Message;
use Parkour\Spot;
use Parkour\Review;
use Parkour\SpotRepository;
use Parkour\UserRepository;
use Parkour\User;
use Parkour\UserStorage;
use Parkour\ReviewRepository;

/**
 * Twig declaration.
 *
 * @var \Twig\Environment $twig
 */


$action = $_REQUEST['action'] ?? '';
$spot_id = $_REQUEST['spot_id'] ?? NULL;
$comment = $_POST['comment'] ?? NULL;
$rating = $_POST['rating'] ?? NULL;
$content = '';


if (isset($_SESSION['user_id'])) {
  $repo = new UserRepository();
  $user = $repo->getUser($_SESSION['user_id']);
  if ($user instanceof User) {
    UserStorage::setLoggedInUser($user);
  }
}

switch ($action) {

  case 'delete':
    if (isset($_SESSION['user_id'], $_SESSION['username'])) {
      $user_id = UserStorage::getLoggedInUser()->getUserId();
      $repo = new SpotRepository();
      $spot = $repo::getSpot($spot_id);
      $spotOwner = $spot->getUserId();
      if ($spotOwner === $user_id) {
        delete_spot($spot_id);
        remove_directory($spot_id);
        Message::setMessage("Spot wurde erfolgreich gelöscht");
        $content = header("Location: index.php");
      }
    }
    else {
      Message::setMessage("Sie müssen angemeldet sein um diesen Spot löschen zu können");
      $content = header("Location: index.php");
    }
    break;

  case 'edit':

    $form = $twig->load('spotform.html.twig');
    $repo = new SpotRepository();
    $spot = $repo::getSpot($spot_id);
    $content = $form->render([
      'spot' => $spot,
      'form' => $form,
      'spot_id' => $spot_id,
      'cities' => get_all_cities(),
    ]);
    break;

  case 'add':
    $spot = new Spot($_REQUEST);
    $repository = new SpotRepository();
    $authorized = TRUE;
    if (!empty($spot_id)) {
      $spot = $repository::getSpot($spot_id);
    }
    elseif (!empty($values)) {
      $spot = $values;
    }

    if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
      $authorized = FALSE;
    }
    $form = $twig->load('spotform.html.twig');
    $content = $form->render([
      'spot' => $spot,
      'form' => $form,
      'spot_id' => $spot_id,
      'cities' => get_all_cities(),
      'authorized' => $authorized,
    ]);
    break;

  case 'images':
    $content = Image::render_images($spot_id);
    break;

  case 'submit':
    $errors = validate_form_submission($_POST);
    if (empty($errors)) {
      $spot = new Spot($_POST);
      $spot->save();
      if (empty($spot_id)) {
        Message::setMessage("Neuer Spot wurde erfolgreich hinzugefügt");
      }
      else {
        Message::setMessage("Änderungen wurden erfolgreich übernommen");
      }
      if (!empty($_FILES['my_file'])) {
        try {
          Image::upload_image($spot->getSpotId(), $_FILES['my_file']);
        }
        catch (FileExistsException $e) {
          Message::setMessage($e->getMessage());
        }
      }
      $content = header("Location: ../../../index.php");
    }
    else {
      if (empty($spot_id)) {
        $content = header("Location: index.php?action=add");
      }
      else {
        $content = header("Location: index.php?spot_id=$spot_id&action=edit");
      }
    }

    break;

  case 'submit_description':
    $username = UserStorage::getLoggedInUser()->getUsername();
    Review::insertDescription($spot_id, $username, $comment, $rating);
    header("Location: ../../../index.php?spot_id=$spot_id&action=detail_view");
    break;

  case 'detail_view':
    $repository = new SpotRepository();
    $spot = $repository->getSpot($spot_id);
    $debug = $spot->getUsername();
    $username = "";
    $user_id = "";
    if (($user = UserStorage::getLoggedInUser())) {
      $username = $user->getUsername();
    }
    if (($user = UserStorage::getLoggedInUser())) {
      $user_id = $user->getUserId();
    }
    $template = $twig->load('detailView.html.twig');
    $content = $template->render([
      'spot' => $spot,
      'spot_id' => $spot_id,
      'rating' => $rating,
      'username' => $username,
      'user_id' => $user_id,
    ]);
    break;

  case 'delete_description':
    Review::loadById($_GET['description_id'])->delete();
    header("Location: ../../../index.php?spot_id=$spot_id&action=detail_view");
    break;

  case 'delete_image':
    $image_id = $_GET['image_id'];
    Image::deleteImage($image_id);
    $count = Image::check_dir($spot_id);
    if ($count >= 1) {
      header("Location: index.php?action=images&spot_id=" . $spot_id . "");
    }
    else {
      header("Location: index.php");
    }
    break;

  case 'register':
    $template = $twig->load('registration.html.twig');
    $content = $template->render();
    break;

  case 'submitRegistration':
    $errors = validate_registration($_POST);
    if (empty($errors)) {
      $user = new User($_POST);
      $user->save();
      mailing($_POST);
      header("Location: index.php");
    }
    else {
      $message = Message::getMessage(implode("<br>", $errors));
      $template = $twig->load('registration.html.twig');
      $content = $template->render([
        'message' => $message,
      ]);
    }
    break;

  case 'login':
    $template = $twig->load('loginform.html.twig');
    $content = $template->render();
    break;

  case 'logout':
    unset($_SESSION['user_id'], $_SESSION['username']);
    $template = $twig->load('loginform.html.twig');
    $content = $template->render([]);
    break;

  case 'submitLogin':
    $username = $_POST['username'];
    $pwinput = $_POST['password'];
    $repo = new UserRepository();
    $user = $repo->getUserByName($username);
    if ($user->authenticate($pwinput) === TRUE) {
      $user->login();
      header('Location:index.php');
    }
    else {
      $template = $twig->load('loginform.html.twig');
      $content = $template->render();
    }
    break;

  case 'verify':
    $username = $_GET['username'];
    $repo = new UserRepository();
    $user = $repo->getUserByName($username);
    $user->setUserActive();
    $user->save();
    $template = $twig->load('verify.html.twig');
    $content = $template->render([]);
    break;

  default:
    $username = "";
    $repository = new SpotRepository();
    if (($user = UserStorage::getLoggedInUser())) {
      $username = $user->getUsername();
    }

    $spots = $repository->getAllSpots();
    $template = $twig->load('spots.html.twig');
    $content = $template->render([
      'title' => 'Alle Spots',
      'spots' => $spots,
      'review' => new ReviewRepository(),
      'username' => $username,
      'message' => Message::getMessage(),
    ]);


}

echo $content;
