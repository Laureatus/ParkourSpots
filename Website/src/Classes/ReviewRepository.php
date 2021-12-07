<?php

namespace Parkour;

use phpDocumentor\Reflection\Types\Mixed_;

/**
 * Class ReviewRepository.
 *
 * @package Parkour
 */
class ReviewRepository {

  /**
   * Gets all reviews for a given spot id.
   *
   * @param int $spotId
   *
   * @return \Parkour\Review[]
   */
  public function getReviews(int $spotId): array {
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
   * @param int $spotId
   *
   * @return int
   */
  public function getRatingAvg(int $spotId): int {
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

}
