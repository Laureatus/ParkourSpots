<?php

class VerifyCest
{
  public function _before(AcceptanceTester $I)
  {
  }

  // tests
  public function tryToTest(AcceptanceTester $I)
  {
    $I->wantTo("Validate the Registered User");
    $I->amOnPage('https://parkour.lndo.site/index.php?action=verify&username=Lorin&auth_token=24853');
    $I->see('Deine Registrierung war erfolgreich');
    $I->click("Weiter zum Login");
    $I->see("Du hast noch keinen Account?");
  }
}