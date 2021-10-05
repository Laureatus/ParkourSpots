
<!DOCTYPE html>
<html lang="de">

<body>

<?php
include 'src/Scripts/head.php';
include_once 'src/Scripts/functions.inc.php';
if (isset($_GET['spot_id']))
{
$spot_id = ($_GET['spot_id']);
delete_spot($spot_id);
}
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
                include 'src/Scripts/database.php';
                ?>
            </div>
        </article>

    </div>

</section>

</body>

</html>
