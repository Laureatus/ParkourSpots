<?php


namespace Parkour;

class DescriptionForm {
  public static function render($spot_id) {
     return <<<FORM
    <form enctype='multipart/form-data' action='index.php' method='post'>
        <input type='hidden' id='action' name='action' value='submit_description'><br>
        <input type='hidden' id='spot_id' name='spot_id' value='$spot_id'><br>
        <label for='name'>Description:</label><br>
        <textarea style="resize: vertical; height: 250px; width: 300px; word-break: break-word;" maxlength="500" type='text' id='name' name='description' value=''></textarea>
        <input type='submit' name='add' value='Submit'>
    </form>
FORM;
  }
}