<?php

namespace Bassa\Php2\Blog\Repositories\UsersRepository;

//use Bassa\Php2\Blog\Repositories\UsersRepository\UserNotFoundException;
use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\UUID;

class InMemoryUsersRepository implements usersRepositoryInterface {

  /**
   * @var User[]
   */
  private array $users = [];

  /**
   * @param User $user
   */
  public function save(User $user): void {
    $this->users[] = $user;
  }

  /**
   * @param \Bassa\Php2\Blog\UUID $uuid
   *
   * @return \Bassa\Php2\Blog\User
   * @throws \Bassa\Php2\Blog\Repositories\UsersRepository\UserNotFoundException
   */
  public function get(UUID $uuid): ?User {
    foreach ($this->users as $user) {
      if ((string) $user->uuid() === (string) $uuid) {
        return $user;
      }
    }
    throw new UserNotFoundException("Пользователь с ID:$uuid не найден");
  }

  // Добавили метод получения пользователя по username

  /**
   * @throws \Bassa\Php2\Blog\Repositories\UsersRepository\UserNotFoundException
   */
  public function getByUsername(string $username): User {
    foreach ($this->users as $user) {
      if ($user->username() === $username) {
        return $user;
      }
    }
    throw new UserNotFoundException("User not found: $username");
  }

}