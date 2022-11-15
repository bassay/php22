<?php

namespace Bassa\Php2\Blog;

use Bassa\Php2\Person\Name;

class User
{
  private UUID $uuid;
  private Name $username;
  private string $login;

  /**
   * @param \Bassa\Php2\Blog\UUID $id
   * @param \Bassa\Php2\Person\Name $username
   * @param string|null $login
   */
  public function __construct(UUID $uuid, Name $username, ?string $login="guest") {
    $this->uuid = $uuid;
    $this->username = $username;
    // $this->login = (string)($login . rand(1, 999)); // пришлось сделать
    // Бубен, так в БД уникальные записи должны быть
    $this->login = $login;
  }

//  /**
//   * @return int
//   */
//  public function id(): int {
//    return $this->id;
//  }

  public function uuid(): UUID
  {
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
  public function __toString() : string {
    return "Юзер $this->username и логином $this->login" .
      PHP_EOL;
  }



}