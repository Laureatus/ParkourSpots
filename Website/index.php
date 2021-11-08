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
             $content = render_spots_table();
         }
         break;

     case 'edit':
         $content = get_spot_form($spot_id);
         break;

     case 'add':
         $content = get_spot_form();
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

         }
         else {
             $content = get_spot_form($spot_id, $_POST, $errors);
         }
         $content = render_spots_table();
         break;

    case 'submit_description':
        Review::insertDescription($spot_id, $comment, $rating);
        break;


    case 'detail_view':
      $repository = new \Parkour\SpotRepository();
      $spot = $repository->getSpot($spot_id);
      $spot_id = $spot->getSpotId();
      $template = $twig->load('detailView.html.twig');
      $form = $twig->load('reviewform.html.twig');
      $review = new \Parkour\ReviewRepository();
      $content = $template->render([
        'spot' => $spot,
        'form' => $form,
        'spot_id' => $spot_id,
        'review' => $review,
      ]);
      break;

    case 'delete_description':
        Review::loadById($_GET['description_id'])->delete();
        $content = show_detail_view($spot_id);

        break;

    case 'delete_image':
        $image_id = $_GET['image_id'];
        Image::delete_image($image_id);
        $count = Image::check_dir($spot_id);
        if ($count >= 1){
            header("Location: index.php?action=images&spot_id=".$spot_id."");
        }else {
            header("Location: index.php");
        }
        break;

      default:
        $repository = new \Parkour\SpotRepository();
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


