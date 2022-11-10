<?php

namespace Bassa\Php2\Blog;

use Bassa\Php2\Person\Name;

class User
{
  private int $id;
  private Name $username;
  private string $login;

  /**
   * @param int $id
   * @param \Bassa\Php2\Person\Name $username
   * @param string $login
   */
  public function __construct(int $id, Name $username, string $login) {
    $this->id = $id;
    $this->username = $username;
    $this->login = $login;
  }

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * @return \Bassa\Php2\Person\Name
   */
  public function getUsername(): Name {
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
  public function getLogin(): string {
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
  public function __toString() : string {
    return "Юзер $this->id c именем $this->username и логином $this->login" .
      PHP_EOL;
  }



}