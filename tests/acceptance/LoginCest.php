<?php

use Codeception\Lib\Driver\Db;


class LoginCest

{

  private $username = null;
  private $user_id = null;
    // tests
  public function login(AcceptanceTester $I)
  {
    $username = 'Lorin';
    $user_id = '1';
    $password = 'Lorin';
    $I->wantTo('Login with User');
    $I->amOnPage('index.php?action=login');
    $I->seeInCurrentUrl('index.php?action=login');
    $I->fillField('username', $username);
    $I->fillField('password', $password);
    $I->click('input[type=submit]');
    $this->username = $I->setCookie('user_name', $username);
    $this->user_id = $I->setCookie('user_id', $user_id);
    $I->dontSeeInCurrentUrl('action=login');
  }
}
