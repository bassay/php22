<?php

namespace Bassa\Php2\Blog;

use Bassa\Php2\Person\Name;

class User {

  /**
   * @param \Bassa\Php2\Blog\UUID $id
   * @param \Bassa\Php2\Person\Name $username
   * @param string|null $login
   */
  public function __construct(
    private UUID    $uuid,
    private Name    $username,
    private ?string $login = "guest") {
  }


  public function uuid(): UUID {
    return $this->uuid;
  }

  /**
   * @return \Bassa\Php2\Person\Name
   */
  public function name(): Name {
    return $this->username;
  }

  /**
   * @param \Bassa\Php2\Person\Name $username
   */
  public function setUsername(Name $username): void {
    $this->username = $username;
  }

  /**
   * @return string
   */
  public function username(): string {
    return $this->login;
  }

  /**
   * @param string $login
   */
  public function setLogin(string $login): void {
    $this->login = $login;
  }

  /**
   * @return string
   */
  public function __toString(): string {
    return "Юзер $this->username и логином $this->login" .
      PHP_EOL;
  }


}