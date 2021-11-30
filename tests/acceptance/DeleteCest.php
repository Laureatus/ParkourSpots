<?php
include 'tests/settings.php';
class DeleteCest {
  public function _before(AcceptanceTester $I)
  {
  }

  // tests
  public function delete(AcceptanceTester $I)
  {
    $spot = SPOT_NAME;
    $user = new LoginCest();
    $user->login($I);
    $I->wantTo("Delete a Spot that I created");
    $I->amOnPage('index.php');
    $I->click($spot);
    $I->dontSee("Notice:");
    $I->see('Delete');
    $I->see('Edit');
    $I->click('Delete');
    $I->dontSeeInCurrentUrl('&action=detail_view');
    $I->dontSeeInDatabase('spot', ['name' => 'Spielplatz']);
  }
}