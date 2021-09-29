<?php
//Pseudoklassen auf Links
echo "<nav id='header-nav'>";
echo "   <div class='container' id='desktop-nav'>";
echo "        <div class='row'>";
echo "           <div class='col-6'>";
echo "                <ul>";
echo "                   <li><a href='index.php'>Startseite</a></li>";
echo "                    <li><a href='new-spot.php'>Neuer Spot</a></li>";
echo "               </ul>";
echo "            </div>";
echo "       </div>";
echo "    </div>";
echo "   <div class='container' id='mobile-nav'>";
echo "        <div class='row'>";
echo "           <div class='col-6'>";
echo "                <div id='mobile-nav-dropdown' class='clearfix'>";
echo "                   <div id='mobile-nav-button'><span>&equiv;</span></div>";
echo "                    <div id='mobile-nav-content' class='clearfix'>";
echo "                       <ul>";
echo "                            <li><a href='index.php'>Startseite</a></li>";
echo "                           <li class='active'><a href='new-spot.php'>Neuer Spot</a></li>";
echo "                        </ul>";
echo "                   </div>";
echo "                </div>";
echo "           </div>";
echo "        </div>";
echo "   </div>";
echo "</nav>";
