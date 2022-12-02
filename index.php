<?php

require_once __DIR__ . '/vendor/autoload.php';

//namespace Some\Namespace;
//class One
//{
//}
//echo One::class;

echo "<h1>Привет мир!</h1>";

// Функция с двумя параметрами возвращает строку


//function someFunction(bool $one, int $two = 123,): string
//{
//  return $one . $two;
//}
//// Создаём объект рефлексии
//// Передаём ему имя интересующей нас функции
//$reflection = new ReflectionFunction('someFunction');
//// Получаем тип возвращаемого функцией значения
//echo $reflection->getReturnType()->getName() . "\n <br>";
////echo var_dump($reflection->getReturnType()->getName()) . "\n <br>";
//// Получаем параметры функции
//foreach ($reflection->getParameters() as $parameter) {
//  // Для каждого параметра функции
//  // получаем его имя и тип
//  echo $parameter->getName().'-> ['.$parameter->getType()->getName()."]\n <br>";
//}

use Bassa\Php2\Blog\Like;
use Bassa\Php2\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use Bassa\Php2\Blog\Repositories\PostsRepository;
use Bassa\Php2\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Bassa\Php2\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Bassa\Php2\Blog\UUID;

//$like = new Like();

// для создания лайка нам нужен объект Post и объект User
// мы их можем получить через запрос
//var_dump(new Like());

$sql = new PDO("sqlite:" . __DIR__ . "/blog.sqlite");

$sqlitePostRepository = new SqlitePostsRepository($sql);

try {
  $post = $sqlitePostRepository->get(new UUID('96d25031-41db-4c5e-a5da-3bd2fe39bc66'));
} catch (Exception $e) {  $e->getMessage();}

$sqliteUserRepository = new SqliteUsersRepository($sql);
$user = $sqliteUserRepository->get(new UUID('ffe924fd-53ba-4478-a948-f4710a8e1b92'));

//var_dump($user); die();

$like = new Like(
  UUID::random(),
  $post,
  $user
);

$sqliteLikeRepository = new SqliteLikesRepository($sql);
//$sqliteLikeRepository->save($like);
$like = $sqliteLikeRepository
  ->getByLikeUuid(new UUID('e227c462-ac8b-49e1-8790-6de938b873d6'));
//
//
//echo "<pre>";
//var_dump($like);
//echo "</pre>";