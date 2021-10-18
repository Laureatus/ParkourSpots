<?php
include 'src/Scripts/head.php';
include_once 'src/Scripts/functions.inc.php';
include_once 'src/Scripts/settings.php';

$action = $_REQUEST['action'] ?? '';
$spot_id = $_REQUEST['spot_id'] ?? null;

switch($action) {

     case 'delete':
         if (delete_spot($spot_id) && remove_directory($spot_id)) {
             // Message ...
             // Redirect ...
         }
         break;

     case 'edit':
         $content = get_spot_form($spot_id);
         break;

     case 'add':
         $content = get_spot_form();
         break;

     case 'submit':
         // POST -> Validierung
         $errors = validate_form_submission($_POST);
         if (empty($errors)) {

             $spot_id === null
                ? insert_spot($_POST['name'], $_POST['address'], $_POST['city'], $_POST['rating'])
                : update_spot($spot_id, $_POST['name'], $_POST['address'], $_POST['city'], $_POST['rating']);

             // Weiterleitung
             header('Location: index.php');
         }
         else {
             $content = get_spot_form($spot_id, $_POST, $errors);
         }





     default:
         $content = render_spots_table();
         break;
}

?>

<!DOCTYPE html>
<html lang="de">

<body>
<?php



include 'src/Scripts/navbar.php';
?>

<!-- PARKOUR SPOTS LISTE -->


<section class="container" >
    <div class="col-6">

        <article class="leistungs-box leistung-box-empfohlen">

            <h1>Parkour Spots</h1>
            <div>

                <?php
                echo Message::getMessage();
                ?>
                <?php echo $content; ?>
            </div>
        </article>

    </div>

</section>

</body>

</html>
