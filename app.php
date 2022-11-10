<?php


require_once __DIR__ . '/vendor/autoload.php';

use Bassa\Php2\Blog\Post;
use Bassa\Php2\Person\Name;
use Bassa\Php2\Person\Person;

$post = new Post(new Person(
    new Name('Иван', 'Никитин'),
    new DateTimeImmutable()),
    'Всем привет!'
);
print $post;