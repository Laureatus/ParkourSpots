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
use Parkour\ImageRepository;

/**
 * Twig declaration.
 *
 * @var \Twig\Environment $twig
 */


$action = $_REQUEST['action'] ?? '';
$spotId = $_REQUEST['spot_id'] ?? NULL;
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
      $userId = UserStorage::getLoggedInUser()->getUserId();
      $repo = new SpotRepository();
      $spot = $repo::getSpot($spotId);
      $spotOwner = $spot->getUserId();
      if ($spotOwner === $userId) {
        delete_spot($spotId);
        remove_directory($spotId);
        Message::setMessage("Spot wurde erfolgreich gelöscht");
        header("Location: index.php");
      }
    }
    else {
      Message::setMessage("Sie müssen angemeldet sein um diesen Spot löschen zu können");
      header("Location: index.php");
    }
    break;

  case 'edit':
    $authorized = TRUE;
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
      $authorized = FALSE;
    }
    $form = $twig->load('spotform.html.twig');
    $repo = new SpotRepository();
    $spot = $repo::getSpot($spotId);
    $content = $form->render([
      'spot' => $spot,
      'form' => $form,
      'spot_id' => $spotId,
      'cities' => get_all_cities(),
      'authorized' => $authorized,
    ]);
    break;

  case 'add':
    $spot = new Spot($_REQUEST);
    $repository = new SpotRepository();
    $authorized = TRUE;
    if (!empty($spotId)) {
      $spot = $repository::getSpot($spotId);
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
      'spot_id' => $spotId,
      'cities' => get_all_cities(),
      'authorized' => $authorized,
    ]);
    break;

  case 'images':
    $repo = new ImageRepository();
    $content = $repo->renderImages($spotId);
    break;

  case 'submit':
    $errors = validate_form_submission($_POST);
    if (empty($errors)) {
      $spot = new Spot($_POST);
      $spot->save();
      if (empty($spotId)) {
        Message::setMessage("Neuer Spot wurde erfolgreich hinzugefügt");
      }
      else {
        Message::setMessage("Änderungen wurden erfolgreich übernommen");
      }
      if (!empty($_FILES['my_file'])) {
        try {
          $repo = new ImageRepository();
          $repo->uploadImage($spot->getSpotId(), $_FILES['my_file']);
        }
        catch (FileExistsException $e) {
          Message::setMessage($e->getMessage());
        }
      }
      header("Location: ../../../index.php");
    }
    else {
      if (empty($spotId)) {
        header("Location: index.php?action=add");
      }
      else {
        header("Location: index.php?spot_id=$spotId&action=edit");
      }
    }

    break;

  case 'submit_description':
    $user = UserStorage::getLoggedInUser();
    if ($user instanceof User) {
      $username = $user->getUsername();
    }
    $repo = new ReviewRepository();
    $repo->insertDescription($spotId, $username, $comment, $rating);
    header("Location: ../../../index.php?spot_id=$spotId&action=detail_view");
    break;

  case 'detail_view':
    $repository = new SpotRepository();
    $spot = $repository->getSpot($spotId);
    $debug = $spot->getUsername();
    $username = "";
    $userId = "";
    $user = UserStorage::getLoggedInUser();
    if ($user instanceof User) {
      $username = $user->getUsername();
      $userId = $user->getUserId();
    }

    $template = $twig->load('detailView.html.twig');
    $content = $template->render([
      'spot' => $spot,
      'spot_id' => $spotId,
      'rating' => $rating,
      'username' => $username,
      'user_id' => $userId,
    ]);
    break;

  case 'delete_description':
    Review::loadById($_GET['description_id'])->delete();
    header("Location: ../../../index.php?spot_id=$spotId&action=detail_view");
    break;

  case 'delete_image':
    $image_id = $_GET['image_id'];
    $repo = new ImageRepository();
    $image = $repo->getImage($image_id);
    $image->deleteImage($image_id);
    $count = Image::checkDir($spotId);
    if ($count >= 1) {
      header("Location: index.php?action=images&spot_id=" . $spotId . "");
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
      $message = Message::setMessage(implode("<br>", $errors));
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
    $user = UserStorage::getLoggedInUser();
    if ($user instanceof User) {
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
