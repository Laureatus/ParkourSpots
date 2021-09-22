
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <!-- STYLESHEET -->
    <link rel="stylesheet" type="text/css" href="src/styles.css">
</head>

<body>

<!-- NAVIGATIONSLEISTE -->

<nav id="header-nav">

    <!-- DESKTOP-NAVIGATION -->

    <div class="container" id="desktop-nav">
        <div class="row">
            <div class="col-6">
                <ul>
                    <li class="active"><a href="index.php">Startseite</a></li>
                    <li><a href="new-spot.php">Neuer Spot</a></li>
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
                            <li class="active"><a href="index.php">Startseite</a></li>
                            <li><a href="new-spot.php">Neuer Spot</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


<!-- PARKOUR SPOTS LISTE -->

<section class="container" >
    <div class="col-6">

        <article class="leistungs-box leistung-box-empfohlen">
            <h1>Parkour Spots</h1>
            <div>
                <?php
                include '../Database/database.php';
                ?>
            </div>
        </article>

    </div>

</section>

</body>

</html>
