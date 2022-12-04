<?php

namespace Bassa\Php2\Blog\Repositories\UsersRepository;

use Bassa\Php2\Blog\Exceptions\UserNotFoundException;
use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\UUID;
use Bassa\Php2\Person\Name;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;

class SqliteUsersRepository implements UsersRepositoryInterface {

  public function __construct(
    private PDO $connection,
    private LoggerInterface $logger
  ) {
  }

  public function save(User $user): void {
    // Добавили поле username в запрос
    $statement = $this->connection->prepare(
      'INSERT INTO users (uuid, username, first_name, last_name)
VALUES (:uuid, :username, :first_name, :last_name)'
    );
    $statement->execute([
      ':uuid' => (string) $user->uuid(),
      ':username' => $user->username(),
      ':first_name' => $user->name()->first(),
      ':last_name' => $user->name()->last(),
    ]);

    // Логируем UUID нового Поста
    $this->logger->info("User created: " . $user->uuid());

  }
  // Также добавим метод для получения
  // пользователя по его UUID
  /**
   * @param \Bassa\Php2\Blog\UUID $uuid
   *
   * @return \Bassa\Php2\Blog\User
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   * @throws \Bassa\Php2\Blog\Exceptions\UserNotFoundException
   */
  public function get(UUID $uuid): User {

    $statement = $this->connection->prepare(
      'SELECT * FROM users WHERE uuid = :uuid'
    );
    $statement->execute([':uuid' => $uuid]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    // Бросаем исключение, если пользователь не найден
    if (FALSE === $result) {
      $this->logger->warning("Не существующий UUID объекта User " . $uuid);
      throw new UserNotFoundException(
        "Cannot get user: $uuid"
      );
    }
    return new User(
      new UUID($result['uuid']),
      new Name($result['first_name'], $result['last_name'])
    );
  }

  // Добавили метод получения пользователя по username

  /**
   * @param string $username
   *
   * @return \Bassa\Php2\Blog\User
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   * @throws \Bassa\Php2\Blog\Exceptions\UserNotFoundException
   */
  public function getByUsername(string $username): User {
    $statement = $this->connection->prepare(
      'SELECT * FROM users WHERE username = :username'
    );
    $statement->execute([
      ':username' => $username,
    ]);
    return $this->getUser($statement, $username);
  }

  // Вынесли общую логику в отдельный приватный метод

  /**
   * @param \PDOStatement $statement
   * @param string $username
   *
   * @return \Bassa\Php2\Blog\User
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   * @throws \Bassa\Php2\Blog\Exceptions\UserNotFoundException
   */
  private function getUser(PDOStatement $statement, string $username): User {

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
      throw new UserNotFoundException(
        "Cannot find user: $username"
      );
    }

    // Создаём объект пользователя с полем username
    return new User(new UUID($result['uuid']),
      new Name($result['first_name'], $result['last_name']),
      $result['username'],
    );
  }

}