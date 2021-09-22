<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <!-- STYLESHEET -->
    <link rel="stylesheet" type="text/css" href="src/Styles/styles.css">
</head>

<body>

<?php
include 'src/Scripts/newSpot.php';
?>

<!-- NAVIGATIONSLEISTE -->

<nav id="header-nav">

    <!-- DESKTOP-NAVIGATION -->

    <div class="container" id="desktop-nav">
        <div class="row">
            <div class="col-6">
                <ul>
                    <li><a href="index.php">Startseite</a></li>
                    <li class="active"><a href="new-spot.php">Neuer Spot</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- MOBILE-NAVIGATION -->

    <div class="container" id="mobile-nav">
        <div class="row">
            <div class="col-6">
                <div id="mobile-nav-dropdown" class="clearfix">
                    <div id="mobile-nav-button"><span>&equiv;</span></div>
                    <div id="mobile-nav-content" class="clearfix">
                        <ul>
                            <li><a href="index.php">Startseite</a></li>
                            <li class="active"><a href="new-spot.php">Neuer Spot</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


<!-- NEUER SPOT SCRIPT -->

<section class="container" >
    <div class="col-6">

        <article class="leistungs-box leistung-box-empfohlen">
            <h1>Neuen Spot hinzufügen</h1>
            <div>
                <form>
                    <input type="text" >
                </form>
            </div>
        </article>

    </div>

</section>

</body>

</html>

