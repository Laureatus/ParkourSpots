<?php

include_once 'tests/settings.php';
class ViewSpotCest {
  public function _before(AcceptanceTester $I)
  {
  }

  // tests
  public function tryToTest(AcceptanceTester $I)
  {
    $I->wantTo("View a Spot");
    $I->amOnPage('index.php');
    $I->click(SPOT_NAME);
    $I->dontSee("Notice:");
    $I->dontSee('Edit');
    $I->dontSee('Delete');
  }
}