<?php

use Codeception\Lib\Driver\Db;
use Parkour\SpotRepository;
use Parkour\Spot;
class NewSpotCest
{




  public function _before(AcceptanceTester $I)
  {

  }


  public function tryToTest(AcceptanceTester $I)
  {
    $login = new \LoginCest();
    $login->login($I);
    $I->wantTo("Add A New Spot");
    $I->amOnPage('index.php?action=add');
    $I->see("Spot Name:");
    $I->fillField('name', 'Test123');
    $I->fillField('address', 'Musterstrasse');
    $I->selectOption('form select[name=city]', 'Aadorf');
    $I->click('Submit');
    $I->see('Spielplatz');



    //$I->seeInDatabase('spot', ['user_id' => $user_id, 'name' => $name, 'address' => $address, 'city' => $city,]);









  }
}