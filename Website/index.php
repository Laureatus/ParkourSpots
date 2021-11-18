<?php

include 'autoload.php';
require_once __DIR__.'/bootstrap.php';
include_once 'src/Scripts/functions.inc.php';
include_once 'src/Scripts/settings.php';

use Parkour\FileExistsException;
use Parkour\Image;
use Parkour\Message;
use Parkour\Spot;
use Parkour\Review;
use Parkour\SpotRepository;
use Parkour\UserRepository;
Use Parkour\user;

/** @var \Twig\Environment $twig */


$action = $_REQUEST['action'] ?? '';
$spot_id = $_REQUEST['spot_id'] ?? null;
$comment = $_POST['comment'] ?? null;
$rating = $_POST['rating'] ?? null;
$content = '';


switch($action) {

     case 'delete':
         if (delete_spot($spot_id) && remove_directory($spot_id)) {
             Message::setMessage("Spot wurde erfolgreich gelöscht");
             $content = header("Location: ../../../index.php");
         }
         break;

     case 'edit':

       $form = $twig->load('spotform.html.twig');
       $repo = new \Parkour\SpotRepository();
       $spot = $repo::getSpot($spot_id);
       $content = $form->render([
         'spot' => $spot,
         'form' => $form,
         'spot_id' => $spot_id,
         'cities' => get_all_cities(),
       ]);
         break;

     case 'add':
       $spot = new \Parkour\Spot($_REQUEST);
       $repository = new \Parkour\SpotRepository();
       if (!empty($spot_id)) {
         $spot = $repository::getSpot($spot_id);
       }
       elseif (!empty($values)) {
         $spot = $values;
       }
       $form = $twig->load('spotform.html.twig');

       $content = $form->render([
         'spot' => $spot,
         'form' => $form,
         'spot_id' => $spot_id,
         'cities' => get_all_cities(),
       ]);
       break;

     case 'images':
         $content = Image::render_images($spot_id);
         break;

     case 'submit':
         // POST -> Validierung
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
                 catch(FileExistsException $e) {
                     Message::setMessage($e->getMessage());
                 }
             }
           $content = header("Location: ../../../index.php");
         }
         else {
           if (empty($spot_id)) {
             $content =header("Location: index.php?action=add");
           }
           else {
             $content = header("Location: index.php?spot_id=$spot_id&action=edit");
           }
         }

         break;

    case 'submit_description':
      Review::insertDescription($spot_id, $comment, $rating);
      header("Location: ../../../index.php?spot_id=$spot_id&action=detail_view");
      break;


    case 'detail_view':
      $repository = new SpotRepository();

      $spot = $repository->getSpot($spot_id);

      $template = $twig->load('detailView.html.twig');
      $form = $twig->load('reviewform.html.twig');

      $content = $template->render([
        'spot' => $spot,
        'form' => $form,
        'spot_id' => $spot_id,
        'rating' => $rating,
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
        if ($count >= 1){
            header("Location: index.php?action=images&spot_id=".$spot_id."");
        }else {
            header("Location: index.php");
        }
        break;

    case 'register':
      $template = $twig->load('registration.html.twig');
      $content = $template->render([

      ]);
      break;

    case 'submitRegistration':

      $errors = validate_registration($_POST);
      if (empty($errors)) {
        $user = new user($_POST);
        $user->save();
        mail('lorin.fankhauser@unic.com','Mailhog test', 'Hello from Mailhog');
        header("Location: index.php");
      } else {
          header("Location: index.php?action=register");
          Message::setMessage(implode("<br>",$errors));
      }

      break;


      default:
        $repository = new SpotRepository();
        /** @var \Parkour\Spot[] $spots */
        $spots = $repository->getAllSpots();
        $template = $twig->load('spots.html.twig');
        $content = $template->render([
         'title' => 'Alle Spots',
         'spots' => $spots,
         'review' => new \Parkour\ReviewRepository()
        ]);
}

echo $content;


