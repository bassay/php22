<?php

use Bassa\Php2\Blog\Container\DIContainer;
use Bassa\Php2\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use Bassa\Php2\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Bassa\Php2\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use Bassa\Php2\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Bassa\Php2\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Bassa\Php2\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/vendor/autoload.php';

// Загружаем переменные окружения из файла .env
Dotenv::createImmutable(__DIR__)->safeLoad();

// Создаём объект контейнера ..
$container = new DIContainer();
// .. и настраиваем его:
// 1. подключение к БД
$container->bind(
  PDO::class,
  //  new PDO("sqlite:" . __DIR__ . "/blog.sqlite")
  new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH'])
);

// 2. репозиторий статей
$container->bind(
  PostsRepositoryInterface::class,
  SqlitePostsRepository::class
);


// 3. репозиторий пользователей
$container->bind(
  UsersRepositoryInterface::class,
  SqliteUsersRepository::class
);


// 4. репозиторий Комментариев
$container->bind(
  CommentsRepositoryInterface::class,
  SqliteCommentsRepository::class
);

// 5. репозиторий Лайков
$container->bind(
  LikesRepositoryInterface::class,
  SqliteLikesRepository::class
);

// Выносим объект логгера в переменную
$logger = (new Logger('blog'));

// Включаем логирование в файлы,
// если переменная окружения LOG_TO_FILES
// содержит значение 'yes'
if ('yes' === $_SERVER['LOG_TO_FILES']) {
  $logger
    ->pushHandler(new StreamHandler(
      __DIR__ . '/logs/blog.log'
    ))
    ->pushHandler(new StreamHandler(
      __DIR__ . '/logs/blog.error.log',
      level: Logger::ERROR,
      bubble: FALSE,
    ));
}
// Включаем логирование в консоль,
// если переменная окружения LOG_TO_CONSOLE
// содержит значение 'yes'
if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
  $logger
    ->pushHandler(
      new StreamHandler("php://stdout")
    );
}

$container->bind(
  LoggerInterface::class,
  $logger
);

// Идентификация пользователя
$container->bind(
  IdentificationInterface::class,
  JsonBodyUuidIdentification::class
);

// 6. Логгер monolog
//$container->bind(
//  LoggerInterface::class,
//  // .. ассоциируем объект логгера из библиотеки monolog
//  (new Logger('blog'))
//    ->pushHandler(new StreamHandler(
//      __DIR__ . '/logs/blog.log' // Путь до этого файла
//    ))
//    ->pushHandler(new StreamHandler(
//    // записывать в файл "blog.error.log"
//      __DIR__ . '/logs/blog.error.log',
//      // события с уровнем ERROR и выше,
//      level: Logger::ERROR,
//      // при этом событие не должно "всплывать"
//      bubble: FALSE,
//    ))
//    ->pushHandler(
//      new StreamHandler("php://stdout")
//    )
//);

return $container;