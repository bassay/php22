<?php
use Bassa\Php2\Blog\Post;
use Bassa\Php2\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Bassa\Php2\Blog\Repositories\PostsRepository\SqlitePostRepository;
use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\UUID;
use Bassa\Php2\Person\Name;
use Bassa\Php2\Blog\Comment;

require_once __DIR__ . '/vendor/autoload.php';

// подключаем факер, на всякий для тестов
$faker = Faker\Factory::create('ru_RU');

//Создаём объект подключения к SQLite
$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
$postsRepository = new SqlitePostRepository($connection);

// Создаем объект USER
$user = new User(UUID::random(), new Name('Bass', 'Rus'), "admin2");
// Создаем объект Post
$post = new Post(UUID::random(), $user, 'test title', 'test text' );
// реализация метода save()
//$postsRepository->save($post);

$uuid_post = new UUID('3e9358b7-d993-439c-99e3-c9f7b7a3e54c');
// возращаем объект Post
//var_dump($postsRepository->get($uuid_post));

$comment = new Comment(
  UUID::random(),
  $user,
  $post,
  'test text comment'
);

//var_dump($comment);

// инициализирует репозиторий SqliteCommentsRepository
$commentRepository = new SqliteCommentsRepository($connection);
// сохранияем комент
$commentRepository->save($comment);

$uuid_comment = new UUID('093f8ddb-ad61-427a-b439-34ada5aa414a');
// получаем коммент
$commet = $commentRepository->get($uuid_comment);
echo $comment->getText();
//var_dump();

