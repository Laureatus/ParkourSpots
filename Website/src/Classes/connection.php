<?php


namespace Parkour;
include_once 'src/Scripts/settings.php';

class connection {

  private static $instance = null;

  protected function __clone()
  {
  }

  protected function __construct()
  {
  }

  public static function getInstance(){
    if (self::$instance === null) {
      self::$instance = new DebuggerEcho();
    }
    return self::$instance;
  }

  public static function connect($hostname = "database", $username = "lorin", $database = "parkour", $password = "db_P@ssw0rd"): \PDO
  {
    return new \PDO("mysql:host=$hostname;dbname=$database", $username, $password);
  }

}