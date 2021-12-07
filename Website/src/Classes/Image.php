<?php

namespace Parkour;

/**
 * Class Image.
 *
 * @package Parkour
 */
class Image {

  /**
   * Stores ImageId.
   *
   * @var mixed|null
   */
  private $imageId;

  /**
   * Stores Path to Spot Images Folder.
   *
   * @var mixed|null
   */
  private $path;

  /**
   * Name of the Image.
   *
   * @var mixed|null
   */
  private $name;

  /**
   * Stores the image filesize.
   *
   * @var mixed|null
   */
  private $size;

  /**
   * Stores the spotId.
   *
   * @var mixed|null
   */
  private $spotId;

  /**
   * Image constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    $this->name = $data['name'] ?? NULL;
    $this->path = $data['path'] ?? NULL;
    $this->size = $data['size'] ?? NULL;
    $this->spotId = $data['spot_id'] ?? NULL;
    $this->imageId = $data['image_id'] ?? NULL;
  }

  /**
   * @return mixed|null
   */
  public function getImageId() {
    return $this->imageId;
  }

  /**
   * @param mixed $imageId
   */
  public function setImageId($imageId) {
    $this->imageId = $imageId;
  }

  /**
   * @return mixed
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * @param mixed $path
   */
  public function setPath($path) {
    $this->path = $path;
  }

  /**
   * @return mixed
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getSize() {
    return $this->size;
  }

  /**
   * @param mixed $size
   */
  public function setSize($size) {
    $this->size = $size;
  }

  /**
   * @return mixed
   */
  public function getSpotId() {
    return $this->spotId;
  }

  /**
   * @param mixed $spotId
   */
  public function setSpotId($spotId) {
    $this->spotId = $spotId;
  }

  /**
   *
   */

  /**
   * $file = $_FILES['my_file']
   */


  /**
   *
   */
  public function deleteImage($image_id) {
    $connection = Connection::connect();
    $query = "SELECT * FROM images WHERE image_id=" . $image_id . ".";
    $results = $connection->query($query);
    $results->setFetchMode(\PDO::FETCH_ASSOC);
    foreach ($results as $key => $result) {
      $filepath = TARGETDIR . $result['path'];
      if (is_file($filepath)) {
        unlink($filepath);
      }
      $connection->query('DELETE FROM images WHERE image_id=' . $image_id);
    }
  }

  /**
   *
   */
  public function checkDir($spotId) {
    $connection = Connection::connect();
    $sql = "select count(*) from images where spot_id = $spotId;";
    $res = $connection->query($sql);
    $count = $res->fetchColumn();
    return $count;
  }

}
