<?php
// old test file

require_once __DIR__ . '/vendor/autoload.php';

use Bassa\Php2\Blog\Comment;
use Bassa\Php2\Blog\Post;
use Bassa\Php2\Person\Name;
use Bassa\Php2\Blog\User;

$faker = Faker\Factory::create('ru_RU');


$name = new Name($faker->firstName, $faker->lastName);
$user = new User(rand(1, 10), $name, $faker->country);
$post = new Post(rand(1, 10), $user, $faker->realText);
$commet = new Comment(rand(1, 10), $user, $post,  $faker->realText(20));

print $commet;

