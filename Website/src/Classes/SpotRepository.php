<?php

namespace Parkour;

/**
 * Get All Spots or get a Single Spot by spotID.
 *
 * @package Parkour
 */
class SpotRepository {

  /**
   * Get all Spots in the Database.
   *
   * @return \Parkour\Spot[]
   *   Return Spots Array.
   */
  public function getAllSpots() {
    $connection = Connection::connect();
    $query = "select spot_id, name, address, city, date_format(added_date, '%d.%m.%Y') as added_date from spot inner join location using(city);";
    $q = $connection->query($query);
    $spots = [];
    if ($q instanceof \PDOStatement) {
      $q->setFetchMode(\PDO::FETCH_ASSOC);
      while ($spot = $q->fetch(\PDO::FETCH_ASSOC)) {
        $spots[] = new Spot($spot);
      }
    }

    return $spots;
  }

  /**
   * Get a single spot by id.
   *
   * @param int $spot_id
   *   The ID of the Spot you are looking for.
   *
   * @return \Parkour\Spot
   *   Return new Spot Object.
   */
  public static function getSpot($spot_id) {
    $connection = Connection::connect();
    $statement = $connection->prepare('SELECT spot_id, user_id, name, address, city, added_date FROM spot INNER JOIN location USING(city) WHERE spot_id = ?');

    if ($statement->execute([$spot_id])) {
      $array = $statement->fetch(\PDO::FETCH_ASSOC);
      return new Spot($array);
    }
  }

}
