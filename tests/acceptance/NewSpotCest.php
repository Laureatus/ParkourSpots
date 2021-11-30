<?php

use Codeception\Lib\Driver\Db;
use Parkour\SpotRepository;
use Parkour\Spot;
class NewSpotCest
{




  public function _before(AcceptanceTester $I)
  {
  }

  /**
   * @depends login
   */
  public function tryToTest(AcceptanceTester $I)
  {
    
    $I->wantTo("Add A New Spot");
    $I->amOnPage('index.php');
    $user_id = $I->getUserId("Lorin");

    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = "Lorin";



    $I->click("Neuer Spot");
    $I->see("Spot Name:");
    $I->fillField('name', 'Test123');
    $I->fillField('address', 'Musterstrasse');
    $I->selectOption('form select[name=city]', 'Aadorf');
    $I->click('Submit');
    $I->see('Spielplatz');



    //$I->seeInDatabase('spot', ['user_id' => $user_id, 'name' => $name, 'address' => $address, 'city' => $city,]);









  }
}