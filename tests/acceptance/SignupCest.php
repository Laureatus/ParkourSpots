<?php

include_once 'tests/settings.php';

/**
 * Class SignupCest
 */
class SignupCest {

  /**
   * @param \AcceptanceTester $I
   */
  public function _before(AcceptanceTester $I) {
  }

  /**
   * Tests.
   */
  public function tryToTest(AcceptanceTester $I) {
    $I->wantTo("Signup a new User");
    $I->amOnPage('index.php?action=register');
    $I->fillField('username', USERNAME);
    $I->fillField('email', EMAIL);
    $I->fillField('password', PASSWORD);
    $I->click('Register');
    $I->see('Name');
  }

}
