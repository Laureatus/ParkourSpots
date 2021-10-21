<?php
include 'src/Scripts/head.php';
include_once 'src/Scripts/functions.inc.php';
include_once 'src/Scripts/settings.php';
include_once 'src/Exceptions/FileExistsException.php';

$action = $_REQUEST['action'] ?? '';
$spot_id = $_REQUEST['spot_id'] ?? null;

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
         $content = render_images($spot_id);
         break;

     case 'submit':
         // POST -> Validierung
         $errors = validate_form_submission($_POST);
         if (empty($errors)) {

             if (empty($spot_id)) {
                 $spot_id = insert_spot($_POST['name'], $_POST['address'], $_POST['city'], $_POST['rating']);
                 Message::setMessage("Neuer Spot wurde erfolgreich hinzugefügt");
             }
             else {
                 update_spot($spot_id, $_POST['name'], $_POST['address'], $_POST['city'], $_POST['rating']);
                 Message::setMessage("Änderungen wurden erfolgreich übernommen");
             }

             if (!empty($_FILES['my_file'])) {
                 try {
                     upload_image($spot_id, $_FILES['my_file']);
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

    case 'detail_view':
        $content = show_single_spot($spot_id);
        break;

    case 'delete_image':
        $image_id = $_GET['image_id'];
        delete_image($image_id);
        $count = check_dir($spot_id);
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
