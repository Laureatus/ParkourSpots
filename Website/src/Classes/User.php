<?php

namespace Parkour;

/**
 *
 */
class User {

  const STATE_UNCONFIRMED = 'UNCONFIRMED';
  const STATE_ACTIVE = 'ACTIVE';
  const STATE_BLOCKED = 'BLOCKED';

  private $userId;
  private $username;
  private $email;
  private $password;
  private $permissionStatus;
  private $addedDate;
  private $authToken;
  private $state;

  /**
   * @var \PDO
   */
  private $connection;

  /**
   *
   */
  public function __construct(array $userdata) {

    $this->connection = connection::connect();

    $this->userId = $userdata['user_id'] ?? NULL;
    $this->username = $userdata['username'] ?? NULL;
    $this->email = $userdata['email'] ?? NULL;
    $this->password = $userdata['password'] ?? NULL;
    $this->permissionStatus = 2 ?? NULL;
    $this->state = $userdata['state'] ?? NULL;
    $this->addedDate = $userdata['added_date'] ?? NULL;
    $this->authToken = rand(10000, 99999) ?? NULL;
  }

  /**
   *
   */
  public function setUserActive() {
    $this->state = self::STATE_ACTIVE;
  }

  /**
   *
   */
  public function setUserBlocked() {
    $this->state = self::STATE_BLOCKED;
  }

  /**
   *
   */
  private function insert() {

    $fields = [
      'username' => ':username',
      'email' => ':email',
      'password' => ':password',
      'permission_status' => ':permission_status',
      'auth_token' => ':auth_token',
      'state' => ':state',
    ];

    $f = implode(', ', array_keys($fields));
    $v = implode(', ', array_values($fields));

    $statementUser = sprintf('INSERT INTO users (%s) VALUES (%s);', $f, $v);
    $insertUser = $this->connection->prepare($statementUser);
    $hash = password_hash($this->password, PASSWORD_BCRYPT);
    $result = $insertUser->execute([
      ':username' => $this->username,
      ':email' => $this->email,
      ':password' => $hash,
      ':permission_status' => $this->permissionStatus,
      ':auth_token' => $this->authToken,
      ':state' => self::STATE_UNCONFIRMED,
    ]);
    return $result;
  }

  /**
   *
   */
  private function update() {

    $fields = [
      'username' => $this->username,
      'email' => $this->email,
      // 'password',
      // 'active' => $this->active,
      'permission_status' => $this->permissionStatus,
      'auth_token' => $this->authToken,
    ];

    if (!empty($this->state)) {
      $fields['state'] = $this->state;
    }

    $updates = [];
    foreach (array_keys($fields) as $field) {
      $updates[] = sprintf('%s=:%s', $field, $field);
    }

    $update_string = implode(', ', $updates);

    $statementUser = sprintf('UPDATE users SET %s WHERE user_id=:user_id;', $update_string);
    $updateUser = $this->connection->prepare($statementUser);

    $fields['user_id'] = $this->userId;

    $params = [];
    foreach ($fields as $key => $value) {
      $params[sprintf(":%s", $key)] = $value;
    }

    $result = $updateUser->execute($params);
    return $result;
  }

  /**
   *
   */
  public function save() {
    return empty($this->userId) ? $this->insert() : $this->update();
  }

  /**
   *
   */
  public function authenticate($pwinput) {
    $password = $this->password;
    $query = 'select password from users where username = "$this->username";';
    $connection = connection::connect();
    $q = $connection->query($query);
    $q->setFetchMode(\PDO::FETCH_ASSOC);
    while ($hash = $q->fetch(\PDO::FETCH_COLUMN)) {
      $pwhash = $hash;
    }
    if (password_verify($pwinput, $password) === TRUE && $this->state === self::STATE_ACTIVE) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }


  /**
   * Set username and UserId as Session variables
   */
  public function login() {
    $_SESSION['user_id'] = $this->userId;
    $_SESSION['username'] = $this->username;
  }

  /**
   * @return mixed
   */
  public function getUserId() {
    return $this->userId;
  }

  /**
   * @param mixed $userId
   */
  public function setUserId($userId) {
    $this->userId = $userId;
  }

  /**
   * @return mixed
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * @param mixed $username
   */
  public function setUsername($username) {
    $this->username = $username;
  }

  /**
   * @return mixed
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @param mixed $email
   */
  public function setEmail($email) {
    $this->email = $email;
  }

  /**
   * @return mixed
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @param mixed $password
   */
  public function setPassword($password) {
    $this->password = $password;
  }

  /**
   * @return mixed
   */
  public function getPermissionStatus() {
    return $this->permissionStatus;
  }

  /**
   * @param mixed $permissionStatus
   */
  public function setPermissionStatus($permissionStatus) {
    $this->permissionStatus = $permissionStatus;
  }

  /**
   * @return mixed
   */
  public function getAddedDate() {
    return $this->addedDate;
  }

  /**
   * @param mixed $addedDate
   */
  public function setAddedDate($addedDate) {
    $this->addedDate = $addedDate;
  }

  /**
   * @return mixed
   */
  public function getAuthToken() {
    return $this->authToken;
  }

  /**
   * @param mixed $authToken
   */
  public function setAuthToken($authToken) {
    $this->authToken = $authToken;
  }

}
