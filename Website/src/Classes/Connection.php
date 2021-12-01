<?php

namespace Parkour;

include_once 'src/Scripts/settings.php';

/**
 * Class Connection.
 */
class Connection {
  private static $instance = NULL;

  /**
   * Creates a single instance of Connection.
   *
   * @return \Parkour\Connection|null
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
   * @param string $username
   * @param string $database
   * @param string $password
   *
   * @return \PDO
   */
  public static function connect($hostname = "database", $username = "lorin", $database = "parkour", $password = "db_P@ssw0rd"): \PDO {
    return new \PDO("mysql:host=$hostname;dbname=$database", $username, $password);
  }

}
