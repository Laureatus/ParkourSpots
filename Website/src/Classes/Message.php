<?php

namespace Parkour;

/**
 * Class Message.
 */
class Message {

  /**
   * @param $message
   */
  public static function setMessage($message) {
    $_SESSION['message'] = $message;
  }

  /**
   * @return mixed
   */
  public static function getMessage() {
    if (isset($_SESSION['message'])) {
      $message = $_SESSION['message'];
      unset($_SESSION['message']);
      return $message;
    }
  }

}
