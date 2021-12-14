<?php

namespace Parkour;

/**
 * User
 */
class User {

  const STATE_UNCONFIRMED = 'UNCONFIRMED';
  const STATE_ACTIVE = 'ACTIVE';
  const STATE_BLOCKED = 'BLOCKED';

  /**
   * ID of the User.
   *
   * @var mixed
   */
  private $userId;

  /**
   * Name of the User.
   *
   * @var mixed
   */
  private $username;

  /**
   * Email of the User.
   *
   * @var mixed
   */
  private $email;

  /**
   * Encrypted Password of the User.
   *
   * @var string
   */
  private $password;

  /**
   * Permission Status of the User.
   *
   * @var mixed
   */
  private $permissionStatus;

  /**
   * Timestamp when the User registered his account.
   *
   * @var mixed|null
   */
  private $addedDate;

  /**
   * Token needed for Authentiation.
   *
   * @var int
   */
  private $authToken;

  /**
   * Shows if the user is active, inactive or banned.
   *
   * @var mixed|null
   */
  private $state;

  /**
   * Connection to the Database.
   *
   * @var \PDO
   */
  private $connection;

  /**
   * User constructor.
   *
   * @param array $userdata
   *   Array with all the necessary User data.
   */
  public function __construct(array $userdata) {

    $this->connection = Connection::connect();

    $this->userId = $userdata['user_id'] ?? NULL;
    $this->username = $userdata['username'] ?? NULL;
    $this->email = $userdata['email'] ?? NULL;
    $this->password = $userdata['password'] ?? NULL;
    $this->permissionStatus = 2;
    $this->state = $userdata['state'] ?? NULL;
    $this->addedDate = $userdata['added_date'] ?? NULL;
    $this->authToken = rand(10000, 99999);
  }

  /**
   * Set the user state to active.
   */
  public function setUserActive() {
    $this->state = self::STATE_ACTIVE;
  }

  /**
   * Set the user state to blocked.
   */
  public function setUserBlocked() {
    $this->state = self::STATE_BLOCKED;
  }

  /**
   * Insert a new User into the database.
   *
   * @return bool
   *   Return True|False
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
   * @return bool
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
   * @return bool
   */
  public function save() {
    return empty($this->userId) ? $this->insert() : $this->update();
  }

  /**
   * @param string $pwinput
   *
   * @return bool
   */
  public function authenticate($pwinput) {
    $password = $this->password;
    $query = 'select password from users where username = "$this->username";';
    $connection = Connection::connect();
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
   * Set username and UserId as Session variables.
   */
  public function login() {
    $_SESSION['user_id'] = $this->userId;
    $_SESSION['username'] = $this->username;
  }

  /**
   * Get the ID of a User.
   *
   * @return mixed
   *   Return userId
   */
  public function getUserId() {
    return $this->userId;
  }

  /**
   * Set a new ID for the User.
   *
   * @param mixed $userId
   *  The ID of the User.
   */
  public function setUserId($userId) {
    $this->userId = $userId;
  }

  /**
   * Get the Name of the User.
   *
   * @return mixed
   *   Return the username.
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * Set a new name for the user.
   *
   * @param mixed $username
   *   Set a new username.
   */
  public function setUsername($username) {
    $this->username = $username;
  }

  /**
   * Get the Email address of a user.
   *
   * @return mixed
   *   Return an Email address.
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * Set a new Email for the User.
   *
   * @param mixed $email
   *   The email of the User.
   */
  public function setEmail($email) {
    $this->email = $email;
  }

  /**
   * Get the Hashed Password of the User.
   *
   * @return mixed
   *   Return Hashed Password.
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @param string $password
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
   * @param int $authToken
   */
  public function setAuthToken($authToken) {
    $this->authToken = $authToken;
  }

}
