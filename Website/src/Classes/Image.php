<?php

namespace Parkour;

/**
 * Handle Image objects.
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
   *   Array holding image Data.
   */
  public function __construct(array $data) {
    $this->name = $data['name'] ?? NULL;
    $this->path = $data['path'] ?? NULL;
    $this->size = $data['size'] ?? NULL;
    $this->spotId = $data['spot_id'] ?? NULL;
    $this->imageId = $data['image_id'] ?? NULL;
  }

  /**
   * Returns the ID of an Image.
   *
   * @return mixed|null
   *   Return the imageId.
   */
  public function getImageId() {
    return $this->imageId;
  }

  /**
   * Sets a new ID for the Image.
   *
   * @param mixed $imageId
   *   The ID of the Image.
   */
  public function setImageId($imageId) {
    $this->imageId = $imageId;
  }

  /**
   * Return the Filepath of an Image.
   *
   * @return mixed
   *   Return the Filepath.
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Set a new Filepath for the Image.
   *
   * @param mixed $path
   *   Set a new Filepath.
   */
  public function setPath($path) {
    $this->path = $path;
  }

  /**
   * Get the Name of the Image.
   *
   * @return mixed
   *   Return the Image Name
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set a new Name for the Image.
   *
   * @param mixed $name
   *   The name of the Image.
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * Get the Size of the Image.
   *
   * @return mixed
   *   Return Imagesize.
   */
  public function getSize() {
    return $this->size;
  }

  /**
   * Set a new Size for the Image.
   *
   * @param mixed $size
   *   Size of the Image e.g 1080x1200.
   */
  public function setSize($size) {
    $this->size = $size;
  }

  /**
   * Get the ID of the Spot the Image belongs to.
   *
   * @return mixed
   *   Return the SpotId.
   */
  public function getSpotId() {
    return $this->spotId;
  }

  /**
   * Set a new SpotId for the Image.
   *
   * @param mixed $spotId
   *   Set new spotId.
   */
  public function setSpotId($spotId) {
    $this->spotId = $spotId;
  }

  /**
   * Delete an Image.
   *
   * @param mixed $image_id
   *   The imageId of the Image that needs to be deleted.
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
   * Count how many Images are in a Directory.
   *
   * @param mixed $spotId
   *   The ID of a spot.
   *
   * @return mixed
   *   return the amount of images in the directory.
   */
  public function checkDir($spotId) {
    $connection = Connection::connect();
    $sql = "select count(*) from images where spot_id = $spotId;";
    $res = $connection->query($sql);
    $count = $res->fetchColumn();
    return $count;
  }

}
