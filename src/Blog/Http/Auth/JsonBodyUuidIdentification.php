<?php

namespace Bassa\Php2\Http\Auth;

use Bassa\Php2\Blog\Exceptions\AuthException;
use Bassa\Php2\Blog\Exceptions\HttpException;
use Bassa\Php2\Blog\Exceptions\UserNotFoundException;
use Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Bassa\Php2\Blog\UUID;

class JsonBodyUuidIdentification implements IdentificationInterface {
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
  public function user(Request $request): User
  {
    try {
      // Получаем UUID пользователя из JSON-тела запроса;
      // ожидаем, что корректный UUID находится в поле user_uuid
      $userUuid = new UUID($request->jsonBodyField('user_uuid'));
    } catch (HttpException|InvalidArgumentException $e) {
      // Если невозможно получить UUID из запроса -
      // бросаем исключение
      throw new AuthException($e->getMessage());
    }
    try {
      // Ищем пользователя в репозитории и возвращаем его
      return $this->usersRepository->get($userUuid);
    } catch (UserNotFoundException $e) {
      // Если пользователь с таким UUID не найден -
      // бросаем исключение
      throw new AuthException($e->getMessage());
    }
  }
}