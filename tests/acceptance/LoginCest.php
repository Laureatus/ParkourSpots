<?php

use Codeception\Lib\Driver\Db;


class LoginCest

{

  private $username = null;
  private $user_id = null;

  /**
   * @return null
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * @param null $username
   */
  public function setUsername($username): void {
    $this->username = $username;
  }

  /**
   * @return null
   */
  public function getUserId() {
    return $this->user_id;
  }

  /**
   * @param null $user_id
   */
  public function setUserId($user_id): void {
    $this->user_id = $user_id;
  }

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
    $I->dontSeeInCurrentUrl('action=login');
    $this->user_id = $user_id;
    $this->username = $username;
  }


}
