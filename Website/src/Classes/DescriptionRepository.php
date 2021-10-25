<?php


namespace Parkour;
use Parkour\connection;

class DescriptionRepository {


  public function getDescriptions($spot_id){
    $connection = connection::connect();
    // Todo: Prepared Statement einfügen
    $query = "SELECT * FROM description WHERE spot_id=$spot_id" ;
    $description = $connection->query($query);
    $description->setFetchMode(\PDO::FETCH_ASSOC);
    $description->execute();

    $descriptions = [];
    foreach ($description as $result) {
      $descriptions[] = new Description($result);
    }
    return $descriptions;
  }


}