<?php

namespace Parkour;

/**
 * Class UserStorage.
 *
 * @package Parkour
 */
class UserStorage {

  /**
   * User Variable.
   *
   * @var user
   */
  private static $user;

  /**
   * Get the currently logged in User.
   *
   * @return \Parkour\User
   *   Returns A User Object.
   */
  public static function getLoggedInUser() {
    return self::$user;
  }

  /**
   * Set Logged IN User.
   *
   * @param \Parkour\User $user
   *   Assigns itself a User Object.
   */
  public static function setLoggedInUser(User $user) {
    self::$user = $user;
  }

}
