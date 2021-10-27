<?php


namespace Parkour;



class Rating {

  private $rating_id;
  private $rating;
  private $spot_id;

  /**
   * @return mixed
   */
  public function getRatingId() {
    return $this->rating_id;
  }

  /**
   * @param mixed $rating_id
   */
  public function setRatingId($rating_id) {
    $this->rating_id = $rating_id;
  }

  /**
   * @return mixed
   */
  public function getRating() {
    return $this->rating;
  }

  /**
   * @param mixed $rating
   */
  public function setRating($rating) {
    $this->rating = $rating;
  }

  /**
   * @return mixed
   */
  public function getSpotId() {
    return $this->spot_id;
  }

  /**
   * @param mixed $spot_id
   */
  public function setSpotId($spot_id) {
    $this->spot_id = $spot_id;
  }

  function insert_rating($spot_id, $description_id, $rating){
    $connection = connection::connect();
    $statementSpot = "INSERT INTO rating (spot_id, description_id, rating) VALUES (:spot_id, :description_id, :rating)";
    $insertSpot = $connection->prepare($statementSpot);
    $result = $insertSpot->execute([
      ':spot_id' => $spot_id,
      ':description_id' => $description_id,
      ':rating' => $rating
    ]);
    if ($result === TRUE) {
      return $connection->lastInsertId();
    }

    return FALSE;
  }

}