<?php

namespace Bassa\Php2\Blog\Commands;

use Bassa\Php2\Blog\Exceptions\CommandException;
use Bassa\Php2\Blog\Exceptions\UserNotFoundException;
use Bassa\Php2\Person\Name;
use Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\UUID;
use Psr\Log\LoggerInterface;

class CreateUserCommand {

  // Команда зависит от контракта репозитория пользователей,
  // а не от конкретной реализации
  public function __construct(
    private UsersRepositoryInterface $usersRepository,
    private LoggerInterface $logger
  ) {
  }

  public function handle(Arguments $arguments): void
  {
    // Логируем информацию о том, что команда запущена
    // Уровень логирования – INFO
//    var_dump($arguments); die();
    $this->logger->info("Create user command started");
    $username = $arguments->get('username');
    if ($this->userExists($username)) {
      // Логируем сообщение с уровнем WARNING
      $this->logger->warning("User already exists: $username");
      // Вместо выбрасывания исключения просто выходим из функции
      return;
    }
    $uuid = UUID::random();
    $this->usersRepository->save(new User(
      uuid: $uuid,
      username: new Name(
        $arguments->get('first_name'),
        $arguments->get('last_name')
      ),
      login: $username
    ));
    // Логируем информацию о новом пользователе
    $this->logger->info("User created: $uuid");
  }
  private function parseRawInput(array $rawInput): array {
    $input = [];
    foreach ($rawInput as $argument) {
      $parts = explode('=', $argument);
      if (count($parts) !== 2) {
        continue;
      }
      $input[$parts[0]] = $parts[1];
    }
    foreach (['username', 'first_name', 'last_name'] as $argument) {
      if (!array_key_exists($argument, $input)) {
        throw new CommandException(
          "No required argument provided: $argument"
        );
      }
      if (empty($input[$argument])) {
        throw new CommandException(
          "Empty argument provided: $argument"
        );
      }
    }
    return $input;
  }

  private function userExists(string $username): bool {
    try {
      // Пытаемся получить пользователя из репозитория
      $this->usersRepository->getByUsername($username);
    } catch (UserNotFoundException) {
      return FALSE;
    }
    return TRUE;
  }

}
