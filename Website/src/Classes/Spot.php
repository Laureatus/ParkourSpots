<?php

namespace Parkour;

class Spot {

  private $spot_id;
  private $city;
  private $name;
  private $address;
  private $added_date;
  private $lng;
  private $lat;
  private $rating;

  /**
   * @var \Parkour\DescriptionRepository
   */
  private $description_repo;

  /**
   * Spot constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    $this->spot_id = $data['spot_id'] ?? NULL;
    $this->city = $data['city'] ?? NULL;
    $this->name = $data['name'] ?? NULL;
    $this->address = $data['address'] ?? NULL;
    $this->added_date = $data['added_date'] ?? NULL;
    $this->lng = $data['lng'] ?? NULL;
    $this->lat = $data['lat'] ?? NULL;
    $this->rating = $data['rating'] ?? NULL;

    $this->description_repo = new DescriptionRepository();
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

  /**
   * @return mixed
   */
  public function getCity() {
    return $this->city;
  }

  /**
   * @param mixed $city
   */
  public function setCity($city) {
    $this->city = $city;
  }

  /**
   * @return mixed
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getAddress() {
    return $this->address;
  }

  /**
   * @param mixed $address
   */
  public function setAddress($address) {
    $this->address = $address;
  }

  /**
   * @return mixed
   */
  public function getAddedDate() {
    return $this->added_date;
  }

  /**
   * @param mixed $added_date
   */
  public function setAddedDate($added_date) {
    $this->added_date = $added_date;
  }

  /**
   * @return mixed
   */
  public function getLng() {
    return $this->lng;
  }

  /**
   * @param mixed $lng
   */
  public function setLng($lng) {
    $this->lng = $lng;
  }

  /**
   * @return mixed
   */
  public function getLat() {
    return $this->lat;
  }

  /**
   * @param mixed $lat
   */
  public function setLat($lat) {
    $this->lat = $lat;
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

  public function save() {
    $connection = connection::connect();

    if (empty($this->spot_id)) {
      $statementSpot = "INSERT INTO spot (name,address,city,rating) VALUES (:name,:address,:city,:rating)";
      $insertSpot = $connection->prepare($statementSpot);
      $result = $insertSpot->execute([
        ':name' => $this->name,
        ':address' => $this->address,
        ':city' => $this->city,
        ':rating' => $this->rating
      ]);
      if ($result === TRUE) {
        $this->spot_id = $connection->lastInsertId();
        return $this->spot_id;
      }
    }
    else {
      $editStatement = "update spot set name =  '$this->name', address = '$this->address', city = '$this->city', rating = '$this->rating' where spot_id = '$this->spot_id'";
      $editSpot = $connection->prepare($editStatement);
      return $editSpot->execute();
    }

    return FALSE;
  }

  /**
   *
   * @return \Parkour\Description[]
   */
  public function getDescriptions() {

    if (!$this->spot_id) {
      return [];
    }

    return $this->description_repo->getDescriptions($this->spot_id);
  }

  public function delete() {
    // ...
  }
}