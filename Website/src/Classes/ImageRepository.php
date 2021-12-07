<?php


namespace Parkour;


class ImageRepository {

  public function renderImages($spotId) {

    // SELECT * FROM images where spot_id=$spot_id;.
    $connection = Connection::connect();
    $query = "SELECT * FROM images WHERE spot_id=" . $spotId . ".";
    $q = $connection->query($query);
    $q->setFetchMode(\PDO::FETCH_ASSOC);

    $directory = TARGETDIR . $spotId;

    if (!is_dir($directory)) {
      return "Couldn't find enclosing image folder:  " . $directory;
    }

    $handle = opendir($directory);
    if (!$handle) {
      return "Couldn't open $directory for reading.";
    }

    $images = '';
    // Loop über SQL-Result -> id, path, name.
    foreach ($q as $key => $image) {
      $images .= "<img alt='spot-image' src=\"" . TARGETDIR . $image['path'] . "\"><a href=\"index.php?action=delete_image&image_id=" . $image['image_id'] . "&spot_id=$spotId\">Delete</a>";
    }
    closedir($handle);

    return $images;
  }

  public function uploadImage($spotId, $image) {
    if ($image['name'] !== "") {
      $dir = TARGETDIR . $spotId;
      if (is_dir($dir)) {
        $target_dir = $dir;
      }
      else {
        mkdir($dir, 0777, FALSE, NULL);
        $target_dir = $dir;
        chmod($target_dir, 0777);
      }
      $file = $image['name'];
      $path = pathinfo($file);
      $filename = $path['filename'];
      $ext = $path['extension'];
      $temp_name = $image['tmp_name'];
      $path_filename_ext = $target_dir . "/" . $filename . "." . $ext;
      $db_path = "$spotId/$filename.$ext";

      if (file_exists($path_filename_ext)) {
        throw new FileExistsException('Bild existiert bereits bitte wählen sie einen anderen Dateinamen');
      }
      else {
        $connection = Connection::connect();
        $statementSpot = "INSERT INTO images (path, spot_id) VALUES (:db_path, :spot_id)";
        $insertSpot = $connection->prepare($statementSpot);

        $insertSpot->execute([
          ':db_path' => $db_path,
          ':spot_id' => $spotId,
        ]);
        return move_uploaded_file($temp_name, $path_filename_ext);
      }
    }

  }

}