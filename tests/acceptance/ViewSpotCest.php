<?php

include_once 'tests/settings.php';

/**
 * Class ViewSpotCest
 */
class ViewSpotCest {

  /**
   * @param \AcceptanceTester $I
   */
  public function _before(AcceptanceTester $I) {
  }

  /**
   * Tests.
   */
  public function tryToTest(AcceptanceTester $I) {
    $I->wantTo("View a Spot");
    $I->amOnPage('index.php');
    $I->click(SPOT_NAME);
    $I->dontSee("Notice:");
    $I->dontSee('Edit');
    $I->dontSee('Delete');
  }

}
