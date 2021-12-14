<?php

namespace Parkour;

/**
 * UserRepository to handle User Objects.
 *
 * @package Parkour
 */
class UserRepository {

  /**
   * Connection of the Database.
   *
   * @var connection
   */
  protected $connection;

  /**
   * UserRepository constructor.
   */
  public function __construct() {
    $this->connection = Connection::connect();
  }

  /**
   * Get all Users from the Database.
   *
   * @return array
   *   Return Users Array.
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
   * Get a User from the Database by ID.
   *
   * @param int $userId
   *   The ID of the user.
   *
   * @return \Parkour\User
   *   Return new User Object.
   */
  public function getUser($userId) {
    $statement = $this->connection->prepare('select user_id, username, email, password, added_time, state, permission_status, auth_token from users WHERE user_id = ?');

    if ($statement->execute([$userId])) {
      $array = $statement->fetch(\PDO::FETCH_ASSOC);
      return new User($array);
    }
  }

  /**
   * Get a User from the Database by Name.
   *
   * @param string $username
   *   The Name of a User.
   *
   * @return \Parkour\User
   *   Return new User object.
   */
  public function getUserByName($username) {
    $statement = $this->connection->prepare('select user_id, username, email, password, added_time, state, permission_status, auth_token from users WHERE username = ?');
    if ($statement->execute([$username])) {
      $array = $statement->fetch(\PDO::FETCH_ASSOC);
      return new User($array);
    }
  }

}
