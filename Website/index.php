<?php

include 'autoload.php';
include 'src/Scripts/head.php';
include_once 'src/Scripts/functions.inc.php';
include_once 'src/Scripts/settings.php';

use Parkour\FileExistsException;
use Parkour\Image;
use Parkour\Message;
use Parkour\Spot;
use Parkour\Review;

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
             $spot->getSpotId();

             if (empty($spot_id)) {
                 Message::setMessage("Neuer Spot wurde erfolgreich hinzugefügt");
             }
             else {
                 Message::setMessage("Änderungen wurden erfolgreich übernommen");
             }

             if (!empty($_FILES['my_file'])) {
                 try {
                     Image::upload_image($spot_id, $_FILES['my_file']);
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
        Review::insertDescription($_POST['spot_id'], $comment, $rating);
        $content = show_detail_view($spot_id);
        break;


    case 'detail_view':
        $content = show_detail_view($spot_id);
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
         $content = render_spots_table();
         break;
}

?>

<!DOCTYPE html>
<html lang="de">
    <body>
        <?php include 'src/Scripts/navbar.php'; ?>
        <!-- PARKOUR SPOTS LISTE -->
        <section class="container" >
            <div class="col-6">
                <article class="leistungs-box leistung-box-empfohlen">
                    <h1>Parkour Spots</h1>
                    <div>
                        <?php echo Message::getMessage(); ?>
                        <?php echo $content; ?>
                    </div>
                </article>
            </div>
        </section>
    </body>
</html>
