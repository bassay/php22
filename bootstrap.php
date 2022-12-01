<?php

// Подключаем автозагрузчик Composer
use Bassa\Php2\Blog\Container\DIContainer;
use Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Bassa\Php2\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Bassa\Php2\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

require_once __DIR__ . '/vendor/autoload.php';
// Создаём объект контейнера ..
$container = new DIContainer();
// .. и настраиваем его:
// 1. подключение к БД
$container->bind(
  PDO::class,
  new PDO("sqlite:" . __DIR__ . "/blog.sqlite")
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
// Возвращаем объект контейнера
return $container;