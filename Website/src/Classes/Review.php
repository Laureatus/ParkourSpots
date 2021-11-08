<?php


namespace Parkour;
use PDO;

class Review {

  private $description_id;
  private $spot_id;
  private $comment;

  public function __construct(array $fields) {
    $this->comment = $fields['comment'] ?? '';
    $this->description_id = $fields['description_id'] ?? '';
    $this->spot_id = $fields['spot_id'] ?? '';
  }

  /**
   * @return mixed|string
   */
  public function getDescriptionId(): string {
    return $this->description_id;
  }

  /**
   * @param mixed|string $description_id
   */
  public function setDescriptionId(string $description_id) {
    $this->description_id = $description_id;
  }

  /**
   * @return mixed|string
   */
  public function getSpotId(): string {
    return $this->spot_id;
  }

  /**
   * @param mixed|string $spot_id
   */
  public function setSpotId(string $spot_id) {
    $this->spot_id = $spot_id;
  }

  /**
   * @return mixed|string
   */
  public function getDescription(): string {
    return $this->comment;
  }

  /**
   * @param mixed|string $description
   */
  public function setDescription(string $description) {
    $this->description = $description;
  }

  public static function loadById($description_id) {
      $connection = connection::connect();

      // Todo: Prepared Statement einfügen
      $statement = $connection->prepare("SELECT * FROM review WHERE description_id=?") ;
      if ($statement->execute([$description_id])) {
        $array = $statement->fetch(PDO::FETCH_ASSOC);
        return new self($array);
      }
  }

  function delete() {
    if (!empty($this->description_id)) {
      $connection = connection::connect();
      $query = "delete from review where description_id = ?";
      $prepare = $connection->prepare($query);
      return $prepare->execute([$this->description_id]);
    }
  }



  function insertDescription($spot_id, $comment, $rating){
    $connection = Connection::connect();
    $statementSpot = "INSERT INTO review (spot_id, comment, rating) VALUES (:spot_id, :comment, :rating);";
    $insertSpot = $connection->prepare($statementSpot);
    $result = $insertSpot->execute([
      ':spot_id' => $spot_id,
      ':comment' => htmlspecialchars($comment),
      ':rating' => $rating
    ]);
    if ($result === TRUE) {
      return $connection->lastInsertId();
    }

    return FALSE;
  }

}
