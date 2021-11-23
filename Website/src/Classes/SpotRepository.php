<?php

namespace Parkour;
use PDO;
use Parkour\Spot;

class SpotRepository {

  /**
   * @return \Parkour\Spot[]
   */
  public function getAllSpots() {
    $connection = connection::connect();
    $query = "select spot_id, name, address, city, date_format(added_date, '%d.%m.%Y') as added_date from spot inner join location using(city);";
    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);

    $spots = [];
    while($spot = $q->fetch(PDO::FETCH_ASSOC)) {
      $spots[] = new Spot($spot);
    }
    return $spots;
  }

  /**
   * Get a single spot by id.
   *
   * @param int $spot_id
   *
   * @return \Parkour\Spot
   */
  public static function getSpot($spot_id) {
    $connection = connection::connect();
    $statement = $connection->prepare('SELECT spot_id, user_id, name, address, city, added_date FROM spot INNER JOIN location USING(city) WHERE spot_id = ?');

    if ($statement->execute([$spot_id])) {
      $array = $statement->fetch(PDO::FETCH_ASSOC);
      return new Spot($array);
    }
  }

}