<?php


namespace Parkour;


class User {

  private $user_id;
  private $username;
  private $email;
  private $password;
  private $permissionStatus;
  private $addedDate;
  private $active;
  private $authToken;

  public function __construct(array $userdata) {

    $this->user_id = $userdata['user_id'] ?? NULL;
    $this->username = $userdata['username'] ?? NULL;
    $this->email = $userdata['email'] ?? NULL;
    $this->password = $userdata['password'] ?? NULL;
    $this->permissionStatus = 2 ?? NULL;
    $this->addedDate = $userdata['added_date'] ?? NULL;
    $this->active = $userdata['active'] ?? NULL;
    $this->authToken = rand(10000, 99999) ?? NULL;
  }

  public function save() {
    $connection = connection::connect();
      $statementUser = "INSERT INTO users (username,email,password,active,permission_status, auth_token) VALUES(:username,:email,:password,:active,:permission_status, :auth_token);";
      $insertUser = $connection->prepare($statementUser);
      $hash = password_hash($this->password, PASSWORD_BCRYPT);
      $result = $insertUser->execute([
        ':username' => $this->username,
        ':email' => $this->email,
        ':password' => $hash,
        ':active' => $this->active,
        ':permission_status' => $this->permissionStatus,
        ':auth_token' => $this->authToken,
      ]);
      return $result;
    }


  /**
   * @return mixed
   */
  public function getUserId() {
    return $this->user_id;
  }

  /**
   * @param mixed $user_id
   */
  public function setUserId($user_id) {
    $this->user_id = $user_id;
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
  public function getActive() {
    return $this->active;
  }

  /**
   * @param mixed $active
   */
  public function setActive($active) {
    $this->active = $active;
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