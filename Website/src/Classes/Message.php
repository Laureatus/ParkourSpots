<?php

namespace Parkour;

/**
 * Handle Messages using session variables.
 */
class Message {

  /**
   * Set a new Message.
   *
   * @param string $message
   *   Set a new message.
   */
  public static function setMessage($message) {
    $_SESSION['message'] = $message;
  }

  /**
   * Get the Message stored in the SESSION variable.
   *
   * @return mixed
   *   Return the Message.
   */
  public static function getMessage() {
    if (isset($_SESSION['message'])) {
      $message = $_SESSION['message'];
      unset($_SESSION['message']);
      return $message;
    }
  }

}
