<?php

namespace Bassa\Php2\Blog\Repositories\CommentsRepository;

use Bassa\Php2\Blog\Comment;
use Bassa\Php2\Blog\UUID;

interface CommentsRepositoryInterface {

  public function save(Comment $user): void;

  public function get(UUID $uuid): Comment;

}