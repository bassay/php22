<?php


use Bassa\Php2\Blog\Post as Post;

require __DIR__ . '/vendor/autoload.php';


$faker = Faker\Factory::create('ru_RU'); // Если нужен русская локализация, передать её параметром в метод create


echo $faker->firstName;
echo '<br>';
echo $faker->address;
echo '<br>';
echo $faker->text;

$post = new Post(
  uuid: new \Bassa\Php2\Blog\UUID();

);

/**
 * @param \Bassa\Php2\Blog\UUID $uuid
 * @param \Bassa\Php2\Blog\User $user
 * @param string $title
 * @param string $text
 */

//for ($i=0; $i<10; $i++){
//  echo $i;
//}