<?php


namespace Parkour;
use PDO;

class Image {

  private $image_id;
  private $path;
  private $name;
  private $size;
  private $spot_id;



  function render_images($spot_id) {

    // SELECT * FROM images where spot_id=$spot_id;

    $connection = connection::connect();;
    $query = "SELECT * FROM images WHERE spot_id=".$spot_id."." ;
    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);

    $directory = TARGETDIR.$spot_id;

    if (!is_dir($directory)) {
      return "Couldn't find enclosing image folder:  " . $directory;
    }

    $handle = opendir($directory);
    if (!$handle) {
      return "Couldn't open $directory for reading.";
    }

    $images = '';
    // Loop über SQL-Result -> id, path, name
    foreach ($q as $key => $image) {
      $images.= "<img src=\"".TARGETDIR.$image['path']."\"><a href=\"index.php?action=delete_image&image_id=".$image['image_id']."&spot_id=$spot_id\">Delete</a>";
    }
    closedir($handle);

    return $images;
  }

  // $file = $_FILES['my_file']

  function upload_image($spot_id, $image) {
    if ($image['name']!=="") {
      $dir = TARGETDIR.$spot_id;
      if (is_dir($dir)) {
        $target_dir = $dir;
      } else {
        mkdir($dir,0777,false, null);
        $target_dir = $dir;
        chmod($target_dir, 0777);
      }
      $file = $image['name'];
      $path = pathinfo($file);
      $filename = $path['filename'];
      $ext = $path['extension'];
      $temp_name = $image['tmp_name'];
      $path_filename_ext = $target_dir."/".$filename.".".$ext;
      $db_path = "$spot_id/$filename.$ext";

      if (file_exists($path_filename_ext)) {
        throw new FileExistsException('Bild existiert bereits bitte wählen sie einen anderen Dateinamen');
      }
      else {
        $connection = connection::connect();;
        $statementSpot = "INSERT INTO images (path, spot_id) VALUES (:db_path, :spot_id)";
        $insertSpot = $connection->prepare($statementSpot);

        $insertSpot->execute([
          ':db_path' => $db_path,
          ':spot_id' => $spot_id,
        ]);
        return move_uploaded_file($temp_name,$path_filename_ext);
      }


      return $errors;
    }

  }

  function delete_image($image_id){
    $connection = connection::connect();;
    $query = "SELECT * FROM images WHERE image_id=".$image_id.".";
    $results = $connection->query($query);
    $results->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($results as $key => $result) {
      $filepath = TARGETDIR.$result['path'];
      if (is_file($filepath)) {
        unlink($filepath);
      }
      $connection->query('DELETE FROM images WHERE image_id=' . $image_id);
    }
  }

  function check_dir($spot_id){
    $connection = connection::connect();;
    $sql = "select count(*) from images where spot_id = $spot_id;";
    $res = $connection->query($sql);
    $count = $res->fetchColumn();
    return $count;
  }
}