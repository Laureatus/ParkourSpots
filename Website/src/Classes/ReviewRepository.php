<?php


namespace Parkour;
use Parkour\connection;

class ReviewRepository {


  public function getDescriptions($spot_id){
    $connection = connection::connect();
    // Todo: Prepared Statement einfügen
    $query = "SELECT * FROM review WHERE spot_id=$spot_id" ;
    $description = $connection->query($query);
    $description->setFetchMode(\PDO::FETCH_ASSOC);
    $description->execute();

    $descriptions = [];
    foreach ($description as $result) {
      $descriptions[] = new Review($result);
    }
    return $descriptions;
  }

  public function getDescriptionId($spot_id){
    $connection = Connection::connect();
    $query = "SELECT description_id FROM review WHERE spot_id=$spot_id" ;
    $description = $connection->query($query);
    $description->setFetchMode(\PDO::FETCH_ASSOC);
    $description->execute();
    $descriptions = [];
    foreach ($description as $result) {
      $descriptions[] = new Review($result);
    }
    return $descriptions;
  }

  public function getRating($description_id){
    $connection = connection::connect();
    // Todo: Prepared Statement einfügen
    $query = "SELECT * FROM review WHERE description_id=$description_id" ;
    $description = $connection->query($query);
    $description->setFetchMode(\PDO::FETCH_ASSOC);
    $description->execute();

    $descriptions = [];
    foreach ($description as $result) {
      $descriptions[] = new Review($result);
    }
    return $descriptions;
  }

  public function selectRatingAvg($spot_id){
    $connection = connection::connect();
    // Todo: Prepared Statement einfügen
    $query = $connection->prepare("SELECT CAST(AVG(rating) AS DECIMAL(10,0)) FROM review WHERE spot_id=$spot_id;");
    $query->execute();
    $result = $query->fetchColumn();
    if ($result !== NULL){
      return $result;
    }
    else {
      return 0;
    }

  }

}