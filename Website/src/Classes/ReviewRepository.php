<?php

namespace Parkour;

/**
 * Class ReviewRepository.
 *
 * @package Parkour
 */
class ReviewRepository {

  /**
   * Gets all reviews for a given spot id.
   *
   * @param mixed $spotId
   *
   * @return \Parkour\Review[]
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
   * @param mixed $spotId
   *
   * @return int
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
   * @param int $spot_id
   * @param string $username
   * @param string $comment
   * @param int $rating
   *
   * @return false|string
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
