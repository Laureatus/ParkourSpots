<?php

include_once 'tests/settings.php';

/**
 * Class ViewOwnSpotCest
 */
class ViewOwnSpotCest {

  /**
   * @param \AcceptanceTester $I
   */
  public function _before(AcceptanceTester $I) {
  }

  /**
   * Tests.
   */
  public function viewOwnSpot(AcceptanceTester $I) {
    $spot = SPOT_NAME;
    $user = new LoginCest();
    $user->login($I);
    $I->wantTo("View a Spot that I created");
    $I->amOnPage('index.php');
    $I->click($spot);
    $I->dontSee("Notice:");
    $I->see('Delete');
    $I->see('Edit');
  }

}
