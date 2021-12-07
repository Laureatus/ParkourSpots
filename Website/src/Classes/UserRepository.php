<?php

namespace Parkour;

/**
 * Class UserRepository.
 *
 * @package Parkour
 */
class UserRepository {

  protected $connection;

  /**
   * UserRepository constructor.
   */
  public function __construct() {
    $this->connection = Connection::connect();
  }

  /**
   * @return array
   */
  public function getAllUsers() {

    $query = "select * from users;";
    $q = $this->connection->query($query);
    $q->setFetchMode(\PDO::FETCH_ASSOC);

    $users = [];
    while ($user = $q->fetch(\PDO::FETCH_ASSOC)) {
      $users[] = new User($user);
    }
    return $users;
  }

  /**
   * @param $user_id
   *
   * @return \Parkour\User
   */
  public function getUser($user_id) {
    $statement = $this->connection->prepare('select user_id, username, email, password, added_time, state, permission_status, auth_token from users WHERE user_id = ?');

    if ($statement->execute([$user_id])) {
      $array = $statement->fetch(\PDO::FETCH_ASSOC);
      return new User($array);
    }
  }

  /**
   * @param $username
   *
   * @return \Parkour\User
   */
  public function getUserByName($username) {
    $statement = $this->connection->prepare('select user_id, username, email, password, added_time, state, permission_status, auth_token from users WHERE username = ?');
    if ($statement->execute([$username])) {
      $array = $statement->fetch(\PDO::FETCH_ASSOC);
      return new User($array);
    }
  }

}
