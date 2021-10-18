
<!DOCTYPE html>
<html lang="de-CH">

<body>
<h1>Bilder</h1>

<div id="images">
<?php
    $spot_id = $_GET['spot_id'];
    $directory = "src/uploads/$spot_id";
    if (!is_dir($directory)) {
        echo "Couldn't find enclosing image folder:  " . $directory;
    }
    else {
        $handle = opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                $filepath = $directory . '/' . $file;
                if (is_file($filepath)) {
                    echo "<img src=\"$filepath\">\n";
                }
            }
            closedir($handle);
        }
        else {
            echo "Couldn't open $directory for reading.";
        }
    }
?>
</div>
</body>

</html>
