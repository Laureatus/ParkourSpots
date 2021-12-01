<?php
include_once 'tests/settings.php';
use Codeception\Lib\Driver\Db;
use Parkour\SpotRepository;
use Parkour\Spot;
class NewSpotCest
{

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
    $I->dontSeeInCurrentUrl('?action=add');
    $I->see('Neuer Spot wurde erfolgreich hinzugefügt');
    $I->seeInDatabase('spot', ['name' => SPOT_NAME]);
  }

  public function newSpotDenied(AcceptanceTester $I)
  {
    $I->wantTo("Restrict access to Form for unregistered Users");
    $I->amOnPage('index.php?action=add');
    $I->see("Sie müssen angemeldet sein um diese Seite sehen zu können");
  }



}