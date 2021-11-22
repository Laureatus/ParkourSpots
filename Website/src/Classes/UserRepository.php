<?php

namespace Parkour;
use Parkour\user;
use PDO;

class UserRepository {

  protected $connection;

  public function __construct() {
    $this->connection = connection::connect();
  }

  /**
   * @return array
   */
  public function getAllUsers() {

    $query = "select * from users;";
    $q = $this->connection->query($query);
    $q->setFetchMode(PDO::FETCH_ASSOC);

    $users = [];
    while($user = $q->fetch(PDO::FETCH_ASSOC)) {
      $users[] = new user($user);
    }
    return $users;
  }


  public function getUser($user_id) {
    $statement = $this->connection->prepare('select username, email, password, added_time, active, permission_status, auth_token from users WHERE user_id = ?');

    if ($statement->execute([$user_id])) {
      $array = $statement->fetch(PDO::FETCH_ASSOC);
      return new user($array);
    }
  }

  public function getUserByName($username) {
    $statement = $this->connection->prepare('select user_id, username, email, password, added_time, permission_status, auth_token from users WHERE username = ?');
    if ($statement->execute([$username])) {
      $array = $statement->fetch(PDO::FETCH_ASSOC);
      return new user($array);
    }
  }


}