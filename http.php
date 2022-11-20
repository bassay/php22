<?php
//use Bassa\Php2\Blog\Http\Request;
//
//require_once __DIR__ . '/vendor/autoload.php';
//
//// Создаём объект запроса из суперглобальных переменных
//$request = new Request($_GET, $_SERVER);
//
//// Получаем данные из объекта запроса
//$parameter = $request->query('some_parameter');
//$header = $request->header('Some-Header');
//$path = $request->path();
//
//echo 'Hello from PHP';

//use Bassa\Php2\Blog\Http\Request;
//use Bassa\Php2\Blog\Http\SuccessfulResponse;
//
//require_once __DIR__ . '/vendor/autoload.php';
//
//$request = new Request($_GET, $_SERVER);
//
//$parameter = $request->query('some_parameter');
//$header = $request->header('Some-Header');
//$path = $request->path();
//
//// Создаём объект ответа
//$response = new SuccessfulResponse([
//  'message' => 'Hello from PHP',
//]);
//// Отправляем ответ
//$response->send();


use Bassa\Php2\Blog\Exceptions\AppException;
use Bassa\Php2\Blog\Exceptions\HttpException;
use Bassa\Php2\Blog\Http\Actions\Post\FindByUuid;
use Bassa\Php2\Blog\Http\Actions\Users\FindByUsername;
use Bassa\Php2\Blog\Http\ErrorResponse;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Repositories\UsersRepository\SqliteUsersRepository;


require_once __DIR__ . '/vendor/autoload.php';

// Создаём объект запроса из суперглобальных переменных
$request = new Request(
  $_GET,
  $_SERVER,
  file_get_contents('php://input')
);

try {
  // Пытаемся получить путь из запроса
  $path = $request->path();
  //  var_dump($path);
  //  die();
} catch (HttpException) {
  // Отправляем неудачный ответ,
  // если по какой-то причине
  // не можем получить путь
  (new ErrorResponse)->send();
  // Выходим из программы
  return;
}

try {
  // Пытаемся получить HTTP-метод запроса
  $method = $request->method();
} catch (HttpException) {
  // Возвращаем неудачный ответ,
  // если по какой-то причине
  // не можем получить метод
  (new ErrorResponse)->send();
  return;
}


$routes = [
  // Добавили ещё один уровень вложенности
  // для отделения маршрутов,
  // применяемых к запросам с разными методами
  'GET' => [
    'http.php/users/show' => new FindByUsername(
      new SqliteUsersRepository(
        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
      )
    ),
    'http.php/posts/show' => new FindByUuid(
      new SqlitePostsRepository(
        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
      )
    ),
  ],
  'POST' => [
    // Добавили новый маршрут
    'http.php/posts/create' => new CreatePost(
      new SqlitePostsRepository(
        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
      ),
      new SqliteUsersRepository(
        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
      )
    ),
  ],
];

// Если у нас нет маршрутов для метода запроса -
// возвращаем неуспешный ответ
if (!array_key_exists($method, $routes)) {
  (new ErrorResponse('Not found'))->send();
  return;
}

// Выбираем действие по методу и пути
$action = $routes[$method][$path];
try {
  $response = $action->handle($request);
} catch (AppException $e) {
  (new ErrorResponse($e->getMessage()))->send();
}


//// Если у нас нет маршрута для пути из запроса -
//// отправляем неуспешный ответ
//if (!array_key_exists($path, $routes)) {
//  (new ErrorResponse('Not found'))->send();
//  return;
//}
//// Выбираем найденное действие
//$action = $routes[$path];
//try {
//  // Пытаемся выполнить действие,
//  // при этом результатом может быть
//  // как успешный, так и неуспешный ответ
//  $response = $action->handle($request);
//} catch (AppException $e) {
//  // Отправляем неудачный ответ,
//  // если что-то пошло не так
//  (new ErrorResponse($e->getMessage()))->send();
//}

// Отправляем ответ
if (isset($response)) {
  $response->send();
}

