<?php ini_set("allow_url_fopen", true);
//Cookie: XDEBUG_SESSION=start

use Bassa\Php2\Blog\Exceptions\AppException;
use Bassa\Php2\Blog\Http\Actions\Post\CreatePost;
use Bassa\Php2\Blog\Http\Actions\Post\FindByUuid;
use Bassa\Php2\Blog\Http\Actions\Users\FindByUsername;
use Bassa\Php2\Blog\Http\ErrorResponse;
use Bassa\Php2\Blog\Http\Request;

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

//
//use Bassa\Php2\Blog\Exceptions\AppException;
//use Bassa\Php2\Blog\Exceptions\HttpException;
//use Bassa\Php2\Blog\Http\Actions\Post\CreatePost;
//use Bassa\Php2\Blog\Http\Actions\Post\DeletePost;
//use Bassa\Php2\Blog\Http\Actions\Post\FindByUuid;
//use Bassa\Php2\Blog\Http\Actions\Users\FindByUsername;
//use Bassa\Php2\Blog\Http\ErrorResponse;
//use Bassa\Php2\Blog\Http\Request;
//use Bassa\Php2\Blog\Repositories\PostsRepository\SqlitePostsRepository;
//use Bassa\Php2\Blog\Repositories\UsersRepository\SqliteUsersRepository;
//use Bassa\Php2\Blog\Http\Actions\Comment\CreateComment;
//use Bassa\Php2\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
//
//require_once __DIR__ . '/vendor/autoload.php';
//
//$request = new Request(
//  $_GET,
//  $_SERVER,
//  file_get_contents('php://input')
//);
//
//try {
//  $path = $request->path();
//} catch (HttpException) {
//  (new ErrorResponse)->send();
//  return;
//}
//try {
//  $method = $request->method();
//} catch (HttpException) {
//  (new ErrorResponse)->send();
//  return;
//}
//
//$routes = [
//  'GET' => [
//    '/http.php/users/show' => new FindByUsername(
//      new SqliteUsersRepository(
//        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
//      )
//    ),
//    '/http.php/posts/show' => new FindByUuid(
//      new SqlitePostsRepository(
//        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
//      )
//    ),
//  ],
//  'POST' => [
//    '/http.php/posts/create' => new CreatePost(
//      new SqlitePostsRepository(
//        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
//      ),
//      new SqliteUsersRepository(
//        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
//      )
//    ),
//    '/http.php/posts/comment' => new CreateComment(
//      new SqlitePostsRepository(
//        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
//      ),
//      new SqliteUsersRepository(
//        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
//      ),
//      new SqliteCommentsRepository(
//        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
//      )
//    ),
//  ],
//
//  'DELETE' => [
//    '/http.php/posts' => new DeletePost(
//      new SqlitePostsRepository(
//        new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
//      ),
//    ),
//  ],
//];
//
//
//if (!array_key_exists($method, $routes)) {
//  (new ErrorResponse('Not found'))->send();
//  return;
//}
//if (!array_key_exists($path, $routes[$method])) {
//  (new ErrorResponse('Empty path'))->send();
//  return;
//}
//
//$action = $routes[$method][$path];
//
//try {
//  $response = $action->handle($request);
//} catch (AppException $e) {
//  (new ErrorResponse($e->getMessage()))->send();
//}
//
//// Отправляем ответ
//if (isset($response)) {
//  $response->send();
//}

/* Урок 5 */
// Подключаем файл bootstrap.php
// и получаем настроенный контейнер

$container = require __DIR__ . '/bootstrap.php';
$request = new Request(
  $_GET,
  $_SERVER,
  file_get_contents('php://input'),
);
try {
  $path = $request->path();
} catch (HttpException) {
  (new ErrorResponse)->send();
  return;
}
try {
  $method = $request->method();
} catch (HttpException) {
  (new ErrorResponse)->send();
  return;
}
// Ассоциируем маршруты с именами классов действий,
// вместо готовых объектов
$routes = [
  'GET' => [
    '/http.php/users/show' => FindByUsername::class,
    '/http.php/posts/show' => FindByUuid::class,
  ],
  'POST' => [
    '/http.php/posts/create' => CreatePost::class,
  ],
];
if (!array_key_exists($method, $routes)) {
  (new ErrorResponse("Route not found: $method $path"))->send();
  return;
}
if (!array_key_exists($path, $routes[$method])) {
  (new ErrorResponse("Route not found: $method $path"))->send();
  return;
}
// Получаем имя класса действия для маршрута
$actionClassName = $routes[$method][$path];
// С помощью контейнера
// создаём объект нужного действия

$action = $container->get($actionClassName);

try {
  $response = $action->handle($request);
} catch (AppException $e) {
  (new ErrorResponse($e->getMessage()))->send();
}
$response->send();