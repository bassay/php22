<?php

namespace Bassa\Php2\Http\Auth;

use Bassa\Php2\Blog\Exceptions\AuthException;
use Bassa\Php2\Blog\Exceptions\HttpException;
use Bassa\Php2\Blog\Exceptions\UserNotFoundException;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class JsonBodyUsernameIdentification implements IdentificationInterface {

  public function __construct(
    private UsersRepositoryInterface $usersRepository
  ) {
  }

  /**
   * @param \Bassa\Php2\Blog\Http\Request $request
   *
   * @return \Bassa\Php2\Http\Auth\User
   * @throws \Bassa\Php2\Blog\Exceptions\AuthException
   */
  public function user(Request $request): User {
    try {
      // Получаем имя пользователя из JSON-тела запроса;
      // ожидаем, что имя пользователя находится в поле username
      $username = $request->jsonBodyField('username');
    } catch (HttpException $e) {
      // Если невозможно получить имя пользователя из запроса -
      // бросаем исключение
      throw new AuthException($e->getMessage());
    }
    try {
      // Ищем пользователя в репозитории и возвращаем его
      return $this->usersRepository->getByUsername($username);
    } catch (UserNotFoundException $e) {
      // Если пользователь не найден -
      // бросаем исключение
      throw new AuthException($e->getMessage());
    }
  }

}