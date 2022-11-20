<?php

namespace Bassa\Php2\Blog\Http\Actions\Users;

use Bassa\Php2\Blog\Exceptions\HttpException;
use Bassa\Php2\Blog\Http\Actions\ActionInterface;
use Bassa\Php2\Blog\Http\ErrorResponse;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\Response;
use Bassa\Php2\Blog\Http\SuccessfulResponse;
use Bassa\Php2\Blog\Exceptions\UserNotFoundException;
use Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class FindByUsername implements ActionInterface {

  // Нам понадобится репозиторий пользователей,
  // внедряем его контракт в качестве зависимости
  public function __construct(
    private UsersRepositoryInterface $usersRepository
  ) {
  }

  // Функция, описанная в контракте
  public function handle(Request $request): Response
  {
    try {
      // Пытаемся получить искомое имя пользователя из запроса
      $username = $request->query('username');
    } catch (HttpException $e) {
      // Если в запросе нет параметра username -
      // возвращаем неуспешный ответ,
      // сообщение об ошибке берём из описания исключения
      return new ErrorResponse($e->getMessage());
    }
    try {
      // Пытаемся найти пользователя в репозитории
      $user = $this->usersRepository->getByUsername($username);
    } catch (UserNotFoundException $e) {
      // Если пользователь не найден -
      // возвращаем неуспешный ответ
      return new ErrorResponse($e->getMessage());
    }
    // Возвращаем успешный ответ
    return new SuccessfulResponse([
      'username' => $user->username(),
      'name' => $user->name()->first() . ' ' . $user->name()->last(),
    ]);
  }
}