<?php


class ViewSpotCest {
  public function _before(AcceptanceTester $I)
  {
  }

  // tests
  public function tryToTest(AcceptanceTester $I)
  {
    $I->wantTo("View a Spot");
    $I->amOnPage('index.php');
    $I->click("Spielplatz");
    $I->dontSee("Notice:");
  }
}