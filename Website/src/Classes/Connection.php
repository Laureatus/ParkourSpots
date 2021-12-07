<?php

namespace Parkour;

include_once 'src/Scripts/settings.php';

/**
 * Creates a single instance of Connection.
 *
 * @param mixed $instance
 *
 * @package Parkour
 */
class Connection {

  /**
   * Holds the instance of connection.
   *
   * @var \Parkour\Connection
   */

  private static $instance;

  /**
   * Creates a single instance of Connection.
   *
   * @return \Parkour\Connection|null
   *   Return a instance of Connection.
   */
  public static function getInstance() {
    if (NULL === self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * Override clone method to keep only one instance.
   */
  protected function __clone() {
  }

  /**
   * Connection constructor.
   */
  protected function __construct() {
  }

  /**
   * Connects to to the database.
   *
   * @param string $hostname
   *   Mysql Hostname.
   * @param string $username
   *   Db username.
   * @param string $database
   *   Db name.
   * @param string $password
   *   Db password.
   *
   * @return \PDO
   *   PDO Object.
   */
  public static function connect($hostname = "database", $username = "lorin", $database = "parkour", $password = "db_P@ssw0rd"): \PDO {
    return new \PDO("mysql:host=$hostname;dbname=$database", $username, $password);
  }

}
