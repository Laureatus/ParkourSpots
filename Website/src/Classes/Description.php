<?php


namespace Parkour;
use PDO;

class Description {

  private $description_id;
  private $spot_id;
  private $description;

  public function __construct(array $fields) {
    $this->description = $fields['description'] ?? '';
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
    return $this->description;
  }

  /**
   * @param mixed|string $description
   */
  public function setDescription(string $description) {
    $this->description = $description;
  }

  public static function loadById($description_id) {
      $connection = connection::connect();;

      // Todo: Prepared Statement einfügen
      $statement = $connection->prepare("SELECT * FROM description WHERE description_id=?") ;
      if ($statement->execute([$description_id])) {
        $array = $statement->fetch(PDO::FETCH_ASSOC);
        return new self($array);
      }
  }

  function delete() {
    if (!empty($this->description_id)) {
      $connection = connection::connect();;
      $query = "delete from description where description_id = ?";
      $prepare = $connection->prepare($query);
      return $prepare->execute([$this->description_id]);
    }
  }



  function insert_description($spot_id, $description){
    $connection = connection::connect();;
    $statementSpot = "INSERT INTO description (spot_id, description) VALUES (:spot_id, :description)";
    $insertSpot = $connection->prepare($statementSpot);
    $result = $insertSpot->execute([
      ':spot_id' => $spot_id,
      ':description' => $description
    ]);
    if ($result === TRUE) {
      return $connection->lastInsertId();
    }

    return FALSE;
  }

}