<?php


namespace Parkour;


class UserStorage {

  private static $user;

  /**
   * @return \Parkour\User
   */
  public static function getLoggedInUser(){
    return self::$user;
  }

  public static function setLoggedInUser(User $user) {
    self::$user = $user;
  }
}