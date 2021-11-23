<?php

namespace Parkour;
use PDO;

class Spot {

  private $spot_id;
  private $city;
  private $name;
  private $address;
  private $added_date;
  private $lng;
  private $lat;
  private $rating;
  private $user_id;

  /**
   * @var \Parkour\ReviewRepository
   */
  private $description_repo;

  /**
   * Spot constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    $this->spot_id = $data['spot_id'] ?? NULL;
    $this->user_id = $data['user_id'] ?? NULL;
    $this->city = $data['city'] ?? NULL;
    $this->name = $data['name'] ?? NULL;
    $this->address = $data['address'] ?? NULL;
    $this->added_date = $data['added_date'] ?? NULL;
    $this->lng = $data['lng'] ?? NULL;
    $this->lat = $data['lat'] ?? NULL;

    $this->description_repo = new ReviewRepository();
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

  /**
   * @return mixed|null
   */
  public function getUserId() {
    return $this->user_id;
  }

  /**
   * @param mixed|null $user_id
   */
  public function setUserId($user_id): void {
    $this->user_id = $user_id;
  }

  public function save() {
    $connection = connection::connect();

    if (empty($this->spot_id)) {
      $statementSpot = "INSERT INTO spot (user_id,name,address,city) VALUES (:user_id, :name,:address,:city);";
      $insertSpot = $connection->prepare($statementSpot);
      $result = $insertSpot->execute([
        ':user_id' => UserStorage::getLoggedInUser()->getUserId(),
        ':name' => $this->name,
        ':address' => $this->address,
        ':city' => $this->city
      ]);
      if ($result === TRUE) {
        $this->spot_id = $connection->lastInsertId();
        return $this->spot_id;
      }
    }
    else {
      $editStatement = "update spot set name =  '$this->name', address = '$this->address', city = '$this->city' where spot_id = '$this->spot_id';";
      $editSpot = $connection->prepare($editStatement);
      return $editSpot->execute();
    }

    return FALSE;
  }

  /**
   *
   * @return \Parkour\review[]
   */
  public function getReviews() {

    if (!$this->spot_id) {
      return [];
    }

    return $this->description_repo->getReviews($this->spot_id);
  }

  public function getRatingAvg() {
    if (!$this->spot_id) {
      return 0;
    }

    return $this->description_repo->getRatingAvg($this->spot_id);
  }

  public function getImages() {
    $connection = Connection::connect();
    $statement = $connection->prepare("SELECT * FROM images WHERE spot_id=?");
    $statement->setFetchMode(\PDO::FETCH_ASSOC);
    $statement->execute([$this->spot_id]);
    $images = [];
    foreach ($statement as $key => $image) {
      $images[] = new Image($image);

    }

    return $images;
  }

  public function getUsername() {
    $username = "";
    $query = "SELECT username FROM spot INNER JOIN users USING(user_id) WHERE spot_id = ".$this->spot_id.";";
    $connection = connection::connect();
    $q = $connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    while ($user = $q->fetch(PDO::FETCH_COLUMN)) {
      $username = $user;
    }
    return $username;
  }



}