<?php ini_set("allow_url_fopen", true);
//Cookie: XDEBUG_SESSION=start

use Bassa\Php2\Blog\Exceptions\AppException;
use Bassa\Php2\Blog\Http\Actions\Comment\CreateComment;
use Bassa\Php2\Blog\Http\Actions\Like\CreateLike;
use Bassa\Php2\Blog\Http\Actions\Like\FindLikeByUuid;
use Bassa\Php2\Blog\Http\Actions\Post\CreatePost;
use Bassa\Php2\Blog\Http\Actions\Post\DeletePost;
use Bassa\Php2\Blog\Http\Actions\Post\FindByUuid;
use Bassa\Php2\Blog\Http\Actions\Users\FindByUsername;
use Bassa\Php2\Blog\Http\ErrorResponse;
use Bassa\Php2\Blog\Http\Request;
use Psr\Log\LoggerInterface;
use Bassa\Php2\Blog\Exceptions\HttpException;

$container = require __DIR__ . '/bootstrap.php';
$request = new Request(
  $_GET,
  $_SERVER,
  file_get_contents('php://input'),
);

// Получаем объект логгера из контейнера
$logger = $container->get(LoggerInterface::class);

try {
  $path = $request->path();
} catch (HttpException $e) {
  $logger->warning($e->getMessage());
  (new ErrorResponse)->send();
  return;
}
try {
  $method = $request->method();
} catch (HttpException $e) {
  $logger->warning($e->getMessage());
  (new ErrorResponse)->send();
  return;
}

$routes = [
  'GET' => [
    '/http.php/user/show' => FindByUsername::class,
    '/http.php/post/show' => FindByUuid::class,
    '/http.php/like/show' => FindLikeByUuid::class,
  ],
  'POST' => [
    '/http.php/posts/create' => CreatePost::class,
    '/http.php/comment/create' => CreateComment::class,
    '/http.php/like/create' => CreateLike::class,
  ],
  'DELETE' => [
    '/http.php/posts' => DeletePost::class,
  ],
];

if (!array_key_exists($method, $routes)
  || !array_key_exists($path, $routes[$method])) {
  // Логируем сообщение с уровнем NOTICE
  $message = "Route not found: $method $path";
  $logger->notice($message);
  (new ErrorResponse($message))->send();
  return;
}

$actionClassName = $routes[$method][$path];

try {
  $action = $container->get($actionClassName);
  $response = $action->handle($request);
} catch (AppException $e) {
  // Логируем сообщение с уровнем ERROR
  $logger->error($e->getMessage(), ['exception' => $e]);
  // Больше не отправляем пользователю
  // конкретное сообщение об ошибке,
  // а только логируем его
  (new ErrorResponse)->send();
}

if (isset($response)){
  $response->send();
}
