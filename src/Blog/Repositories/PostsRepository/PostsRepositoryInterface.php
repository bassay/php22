<?php
namespace Bassa\Php2\Blog\Repositories\PostsRepository;

use Bassa\Php2\Blog\Post;
use Bassa\Php2\Blog\UUID;

interface PostsRepositoryInterface {

  public function save(Post $user): void;

  public function get(UUID $uuid): Post;
}