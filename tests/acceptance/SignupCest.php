<?php

class SignupCest
{
    public function _before(AcceptanceTester $I)
    {
    }
    // tests
    public function tryToTest(AcceptanceTester $I)
    {
      $I->wantTo("Signup a new User");
      $I->amOnPage('index.php?action=register');
      $I->fillField('username', 'Lorin');
      $I->fillField('email', 'Lorin');
      $I->fillField('password', 'Lorin');
      $I->click('Register');
      $I->see('Name');
    }
}
