<?php
include_once 'tests/settings.php';
use Codeception\Lib\Driver\Db;
use Parkour\SpotRepository;
use Parkour\Spot;
class NewSpotCest
{




  public function _before(AcceptanceTester $I)
  {

  }


  public function newSpot(AcceptanceTester $I)
  {
    $login = new \LoginCest();
    $login->login($I);
    $I->wantTo("Add A New Spot");
    $I->amOnPage('index.php?action=add');
    $I->see("Spot Name:");
    $I->fillField('name', SPOT_NAME);
    $I->fillField('address', ADDRESS);
    $I->selectOption('form select[name=city]', CITY);
    $I->click('Submit');
    $I->see(SPOT_NAME);
    $I->seeInDatabase('spot', ['name' => SPOT_NAME]);



    //$I->seeInDatabase('spot', ['user_id' => $user_id, 'name' => $name, 'address' => $address, 'city' => $city,]);









  }
}