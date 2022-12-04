<?php
namespace Bassa\Php2\Http\Auth;

use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\User;

interface IdentificationInterface {
  // Контракт описывает единственный метод,
  // получающий пользователя из запроса
  public function user(Request $request): User;
}