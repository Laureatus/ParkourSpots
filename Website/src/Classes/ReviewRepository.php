<?php

namespace Parkour;

/**
 * Handle Review Objects.
 *
 * @package Parkour
 */
class ReviewRepository {

  /**
   * Gets all reviews for a given spot id.
   *
   * @param mixed $spotId
   *   The ID of the spot.
   *
   * @return array
   *   Return the Reviews of the Spot
   */
  public function getReviews($spotId): array {
    $connection = Connection::connect();
    // @todo Prepared Statement einfügen
    $query = "SELECT * FROM review WHERE spot_id=$spotId";
    $description = $connection->query($query);
    $description->setFetchMode(\PDO::FETCH_ASSOC);
    $description->execute();

    $descriptions = [];
    foreach ($description as $result) {
      $descriptions[] = new Review($result);
    }
    return $descriptions;
  }

  /**
   * Get the Average rating of the Spot.
   *
   * @param mixed $spotId
   *   The ID of the spot.
   *
   * @return int
   *   Return the average Rating of the spot.
   */
  public function getRatingAvg($spotId) {
    $connection = Connection::connect();
    // @todo Prepared Statement einfügen
    $query = $connection->prepare("SELECT CAST(AVG(rating) AS DECIMAL(10,0)) FROM review WHERE spot_id=$spotId;");
    $query->execute();
    $result = $query->fetchColumn();
    if ($result !== NULL) {
      return $result;
    }
    else {
      return 0;
    }

  }

  /**
   * Insert a new Description in the Database.
   *
   * @param int $spot_id
   *   The ID of the spot.
   * @param string $username
   *   The name of the User.
   * @param string $comment
   *   The review text.
   * @param int $rating
   *   The Rating of the Review.
   *
   * @return false|string
   *   Return new Description.
   */
  public function insertDescription($spot_id, $username, $comment, $rating) {
    $connection = Connection::connect();
    if ($rating <= 10 && $rating > 0) {
      $statementSpot = "INSERT INTO review (spot_id, username, comment, rating) VALUES (:spot_id, :username, :comment, :rating);";
      $insertSpot = $connection->prepare($statementSpot);
      $result = $insertSpot->execute([
        ':spot_id' => $spot_id,
        ':username' => $username,
        ':comment' => htmlspecialchars($comment),
        ':rating' => $rating,
      ]);
      if ($result === TRUE) {
        return $connection->lastInsertId();
      }
    }
    return FALSE;
  }

}
