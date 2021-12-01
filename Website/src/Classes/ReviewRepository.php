<?php

namespace Parkour;

/**
 * Class ReviewRepository
 *
 * @package Parkour
 */
class ReviewRepository {

  /**
   * Gets all reviews for a given spot id.
   *
   * @param $spot_id
   *
   * @return \Parkour\Review[]
   */
  public function getReviews($spot_id) {
    $connection = connection::connect();
    // @todo Prepared Statement einfügen
    $query = "SELECT * FROM review WHERE spot_id=$spot_id";
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
   * @param $spot_id
   *
   * @return int|mixed
   */
  public function getRatingAvg($spot_id) {
    $connection = connection::connect();
    // @todo Prepared Statement einfügen
    $query = $connection->prepare("SELECT CAST(AVG(rating) AS DECIMAL(10,0)) FROM review WHERE spot_id=$spot_id;");
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
