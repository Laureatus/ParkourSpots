<?php

include_once 'tests/settings.php';

/**
 * Class LoginCest
 */
class LoginCest {

  private $username = NULL;
  private $user_id = NULL;

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

  /**
   * Tests.
   */
  public function login(AcceptanceTester $I) {
    $username = USERNAME;
    $user_id = USER_ID;
    $password = PASSWORD;
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
