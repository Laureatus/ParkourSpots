<?php

namespace Parkour;

/**
 * Class ReviewForm
 *
 * @package Parkour
 */
class ReviewForm {

  /**
   * @param $spot_id
   *
   * @return string
   */
  public static function render($spot_id) {
    return <<<FORM
    <form enctype='multipart/form-data' action='index.php' method='post'>
        <input type='hidden' id='action' name='action' value='submit_description'><br>
        <input type='hidden' id='spot_id' name='spot_id' value='$spot_id'><br>
        <label for='name'>Review:</label><br>
        <textarea style="resize: vertical; height: 250px; width: 300px; word-break: break-word;" maxlength="500" type='text' id='name' name='comment' value=''></textarea>
        <label for='name'>Rating:</label><br>
        <input type="number" id="rating" name="rating" min="1" max="10" value=""><br>
        <input type='submit' name='add' value='Submit'>
    </form>
FORM;
  }

}
