<?php

namespace Parkour;

/**
 * Creates a Spot Object.
 *
 * @package Parkour
 */
class Spot {

  /**
   * Id of the Spot.
   *
   * @var mixed|null
   */
  private $spotId;

  /**
   * City where the Spot is located.
   *
   * @var mixed|null
   */
  private $city;

  /**
   * Name of the Spot.
   *
   * @var mixed|null
   */
  private $name;

  /**
   * Exact address of the Spot.
   *
   * @var mixed|null
   */
  private $address;

  /**
   * Date when the Spot was added to the Database.
   *
   * @var mixed|null
   */
  private $addedDate;

  /**
   * Longitude of the Spot.
   *
   * @var mixed|null
   */
  private $lng;

  /**
   * Lattitude of the Spot.
   *
   * @var mixed|null
   */
  private $lat;

  /**
   * Rating of the Spot.
   *
   * @var mixed|null
   */
  private $rating;

  /**
   * ID of the user that created the Spot.
   *
   * @var mixed|null
   */
  private $userId;

  /**
   * Stores a Review Repository.
   *
   * @var \Parkour\ReviewRepository
   */
  private $reviewRepository;

  /**
   * Spot constructor.
   *
   * @param arraymixed $data
   *   Array with data from SpotRepository.
   */
  public function __construct(array $data) {
    $this->spotId = $data['spot_id'] ?? NULL;
    $this->userId = $data['user_id'] ?? NULL;
    $this->city = $data['city'] ?? NULL;
    $this->name = $data['name'] ?? NULL;
    $this->address = $data['address'] ?? NULL;
    $this->addedDate = $data['added_date'] ?? NULL;
    $this->lng = $data['lng'] ?? NULL;
    $this->lat = $data['lat'] ?? NULL;

    $this->reviewRepository = new ReviewRepository();
  }

  /**
   * Gets the SpotId.
   *
   * @return mixed
   *   Return the SpotId.
   */
  public function getSpotId() {
    return $this->spotId;
  }

  /**
   * Set a new ID for the Spot.
   *
   * @param int $spotId
   *   Set SpotId.
   *
   * @return void
   */
  public function setSpotId($spotId) {
    $this->spotId = $spotId;
  }

  /**
   * Get the city where the Spot is located in.
   *
   * @return mixed
   *   Return the city of the spot.
   */
  public function getCity() {
    return $this->city;
  }

  /**
   * Set a new City for the Spot.
   *
   * @param mixed $city
   *   Set the city of the Spot.
   *
   * @return void
   */
  public function setCity($city) {
    $this->city = $city;
  }

  /**
   * Get the Name of the Spot.
   *
   * @return mixed
   *   Return the Name of the Spot.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set a new Name for the Spot.
   *
   * @param mixed $name
   *   Set the Name of the Spot.
   *
   * @return void
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * Get the exact address where the spot is located.
   *
   * @return mixed
   *   Return the Address of the Spot.
   */
  public function getAddress() {
    return $this->address;
  }

  /**
   * Set a new address for the Spot.
   *
   * @param mixed $address
   *   Set the Address of the Spot.
   *
   * @return void
   */
  public function setAddress($address) {
    $this->address = $address;
  }

  /**
   * Get the Date when the Spot was added to the Database.
   *
   * @return mixed
   *   Return the addedDate
   */
  public function getAddedDate() {
    return $this->addedDate;
  }

  /**
   * Set a new addedDate for the Spot.
   *
   * @param mixed $addedDate
   *   Set addedDate.
   *
   * @return void
   */
  public function setAddedDate($addedDate) {
    $this->addedDate = $addedDate;
  }

  /**
   * Get the Longitude of the Spot.
   *
   * @return mixed
   *   return the longitude.
   */
  public function getLng() {
    return $this->lng;
  }

  /**
   * Set a new Longitude for the Spot.
   *
   * @param mixed $lng
   *   Set Longitude.
   *
   * @return void
   */
  public function setLng($lng) {
    $this->lng = $lng;
  }

  /**
   * Get the Lattitude of the Spot.
   *
   * @return mixed
   *   Return the Lattitude.
   */
  public function getLat() {
    return $this->lat;
  }

  /**
   * Set a new Lattitude for the Spot.
   *
   * @param mixed $lat
   *   Set new Lattitude.
   *
   * @return void
   */
  public function setLat($lat) {
    $this->lat = $lat;
  }

  /**
   * Get the Rating of the Spot.
   *
   * @return mixed
   *   Return Rating.
   */
  public function getRating() {
    return $this->rating;
  }

  /**
   * Set a new Rating for the Spot.
   *
   * @param mixed $rating
   *   Set new Rating.
   *
   * @return void
   */
  public function setRating($rating) {
    $this->rating = $rating;
  }

  /**
   * Get the ID of the user that created the Spot.
   *
   * @return mixed|null
   *   Return UserID
   */
  public function getUserId() {
    return $this->userId;
  }

  /**
   * Set new UserID for the current Spot.
   *
   * @param mixed|null $userId
   *   Set UserID.
   *
   * @return void
   */
  public function setUserId($userId): void {
    $this->userId = $userId;
  }

  /**
   * Save the values for Spot if it already exists otherwise create a new Spot.
   *
   * @return bool|string
   *   Return SpotID or a PDO execute() statement.
   */
  public function save() {
    $connection = Connection::connect();

    if (empty($this->spotId)) {
      $statementSpot = "INSERT INTO spot (user_id,name,address,city) VALUES (:user_id, :name,:address,:city);";
      $insertSpot = $connection->prepare($statementSpot);
      $result = $insertSpot->execute([
        ':user_id' => UserStorage::getLoggedInUser()->getUserId(),
        ':name' => $this->name,
        ':address' => $this->address,
        ':city' => $this->city,
      ]);
      if ($result === TRUE) {
        $this->spotId = $connection->lastInsertId();
        return $this->spotId;
      }
    }
    else {
      $editStatement = "update spot set name =  '$this->name', address = '$this->address', city = '$this->city' where spot_id = '$this->spotId';";
      $editSpot = $connection->prepare($editStatement);
      return $editSpot->execute();
    }

    return FALSE;
  }

  /**
   * Get all Reviews from the selected Spot.
   *
   * @return \Parkour\Review[]
   *   Return Reviews by spotID
   */
  public function getReviews() {

    if (!$this->spotId) {
      return [];
    }

    return $this->reviewRepository->getReviews($this->spotId);
  }

  /**
   * Get the Average Rating of a Spot.
   *
   * @return int|mixed
   *   Return the avg Rating from getRatingAvg.
   */
  public function getRatingAvg() {
    if (!$this->spotId) {
      return 0;
    }

    return $this->reviewRepository->getRatingAvg($this->spotId);
  }

  /**
   * Return all Images of the Spot.
   *
   * @return arraymixed
   *   Return Image Array
   */
  public function getImages() {
    $connection = Connection::connect();
    $statement = $connection->prepare("SELECT * FROM images WHERE spot_id=?");
    $statement->setFetchMode(\PDO::FETCH_ASSOC);
    $statement->execute([$this->spotId]);
    $images = [];
    foreach ($statement as $key => $image) {
      $images[] = new Image($image);

    }

    return $images;
  }

  /**
   * Get the Username of the User that created the Spot.
   *
   * @return mixed|string
   *   Return Username
   */
  public function getUsername() {
    $username = "";
    $query = "SELECT username FROM spot INNER JOIN users USING(user_id) WHERE spot_id = " . $this->spotId . ";";
    $connection = Connection::connect();
    $q = $connection->query($query);
    if ($q instanceof \PDOStatement) {
      $q->setFetchMode(\PDO::FETCH_ASSOC);
      while ($user = $q->fetch(\PDO::FETCH_COLUMN)) {
        $username = $user;
      }
    }
    return $username;
  }

}
