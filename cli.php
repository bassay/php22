<?php

// Подключаем файл bootstrap.php
// и получаем настроенный контейнер
use Bassa\Php2\Blog\Commands\Arguments;
use Bassa\Php2\Blog\Commands\CreateUserCommand;
use Bassa\Php2\Blog\Exceptions\AppException;
use Bassa\Php2\Blog\Commands\Arguments;

$container = require __DIR__ . '/bootstrap.php';
// При помощи контейнера создаём команду
$command = $container->get(CreateUserCommand::class);
try {
  $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
  echo "{$e->getMessage()}\n";
}