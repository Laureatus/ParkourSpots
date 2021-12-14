<?php

namespace Parkour;

// @todo Change the Variables naming so it matches better with the Database Table review and its columns.

/**
 * Handle Review Objects.
 *
 * @package Parkour
 */
class Review {

  /**
   * ID of the description.
   *
   * @var mixed|string
   */
  private $descriptionId;

  /**
   * ID of the Spot.
   *
   * @var mixed|string
   */
  private $spotId;

  /**
   * Username of the User that wrote the Review.
   *
   * @var mixed|string
   */
  private $username;

  /**
   * Comment wrote inside the Review text input field.
   *
   * @var mixed|string
   */
  private $comment;

  /**
   * Rating from 1-10.
   *
   * @var mixed|string
   */
  private $rating;

  /**
   * Review constructor.
   *
   * @param array $fields
   *   Array of values containing all infos of the Review.
   */
  public function __construct(array $fields) {
    $this->username = $fields['username'] ?? '';
    $this->comment = $fields['comment'] ?? '';
    $this->descriptionId = $fields['description_id'] ?? '';
    $this->spotId = $fields['spot_id'] ?? '';
    $this->rating = $fields['rating'] ?? '';
  }

  /**
   * Get the Username of the User that wrote the Review.
   *
   * @return mixed
   *   Return Username
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * Set a new Username for the Review.
   *
   * @param mixed $username
   *   set Username.
   */
  public function setUsername($username) {
    $this->username = $username;
  }

  /**
   * Get the Rating from the Review.
   *
   * @return mixed|string
   *   Return Rating
   */
  public function getRating() {
    return $this->rating;
  }

  /**
   * Set a New rating for the Review.
   *
   * @param mixed $rating
   *   Return Rating.
   */
  public function setRating($rating) {
    $this->rating = $rating;
  }

  /**
   * Get the Description ID of the Review.
   *
   * @return int
   *   Return DescriptionId
   */
  public function getDescriptionId() {
    return $this->descriptionId;
  }

  /**
   * Set a new Description ID for the Review.
   *
   * @param int $descriptionId
   *   Set new Description ID.
   */
  public function setDescriptionId($descriptionId) {
    $this->descriptionId = $descriptionId;
  }

  /**
   * Get the ID of the Spot in which the Review was written.
   *
   * @return int
   *   Return spotId.
   */
  public function getSpotId() {
    return $this->spotId;
  }

  /**
   * Assign the Review to a new Spot.
   *
   * @param int $spotId
   *   Set new spotId.
   */
  public function setSpotId($spotId) {
    $this->spotId = $spotId;
  }

  /**
   * Get the Description written in the Review.
   *
   * @return string
   *   Return the Description.
   */
  public function getComment() {
    return $this->comment;
  }

  /**
   * Set a new Description for the Review.
   *
   * @param string $comment
   *   Set new Description.
   */
  public function setComment($comment) {
    $this->comment = $comment;
  }

  /**
   * Get the Description ID.
   *
   * @param int $description_id
   *   The ID of the description.
   *
   * @return self|void
   *   Return a description.
   */
  public static function loadById($description_id) {
    $connection = Connection::connect();
    // @todo Prepared Statement einfügen
    $statement = $connection->prepare("SELECT * FROM review WHERE description_id=?");
    if ($statement->execute([$description_id])) {
      $array = $statement->fetch(\PDO::FETCH_ASSOC);
      return new self($array);
    }
  }

  /**
   * Delete a Review.
   *
   * @return bool|void
   *   Return TRUE on success and FALSE on failure
   */
  public function delete() {
    if (!empty($this->descriptionId)) {
      $connection = Connection::connect();
      $query = "delete from review where description_id = ?";
      $prepare = $connection->prepare($query);
      return $prepare->execute([$this->descriptionId]);
    }
  }

}
