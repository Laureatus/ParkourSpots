<?php

namespace Parkour;

/**
 * Handle Images and Directories.
 *
 * @package Parkour
 */
class ImageRepository {

  /**
   * Connection to the Database.
   *
   * @var connection
   */
  private $connection;

  /**
   * ImageRepository constructor.
   */
  public function __construct() {
    $this->connection = Connection::connect();
  }

  /**
   * Render the Images from a Spot.
   *
   * @param mixed $spotId
   *   The ID of a spot.
   *
   * @return string
   *   Return Images as HTML String.
   */
  public function renderImages($spotId) {

    $query = "SELECT * FROM images WHERE spot_id=" . $spotId . ".";
    $q = $this->connection->query($query);
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

  /**
   * Upload a new image to the Database.
   *
   * @param mixed $spotId
   *   The ID of a Spot.
   * @param mixed $image
   *   The image name.
   *
   * @return bool
   *   Return TRUE|FALSE
   *
   * @throws \Parkour\FileExistsException
   *   Throw \Parkour\FileExistsException.
   */
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
        $statementSpot = "INSERT INTO images (path, spot_id) VALUES (:db_path, :spot_id)";
        $insertSpot = $this->connection->prepare($statementSpot);

        $insertSpot->execute([
          ':db_path' => $db_path,
          ':spot_id' => $spotId,
        ]);
        return move_uploaded_file($temp_name, $path_filename_ext);
      }
    }

  }

  /**
   * Get a Image using the image ID.
   *
   * @param mixed $imageId
   *   The ID of the Image.
   *
   * @return \Parkour\Image
   *   Return new Image Object.
   */
  public function getImage($imageId) {
    $statement = $this->connection->prepare("SELECT * FROM images WHERE image_id=$imageId");

    if ($statement->execute([$imageId])) {
      $array = $statement->fetch(\PDO::FETCH_ASSOC);
      return new Image($array);
    }
  }

}
