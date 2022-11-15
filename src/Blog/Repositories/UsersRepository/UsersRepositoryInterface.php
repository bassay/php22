<?php

namespace Bassa\Php2\Blog\Repositories\UsersRepository;

use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\UUID;

interface usersRepositoryInterface
{
  public function save(User $user): void;
  public function get(UUID $uuid): User;
  // Добавили метод
  public function getByUsername(string $username): User;
}