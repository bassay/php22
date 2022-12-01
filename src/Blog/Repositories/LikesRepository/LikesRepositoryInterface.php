<?php
namespace Bassa\Php2\Blog\Repositories\LikesRepository;

use Bassa\Php2\Blog\Like;
use Bassa\Php2\Blog\UUID;

interface LikesRepositoryInterface {
  public function save(Like $like):void;
  public function getByPostUuid(UUID $uuid): Like;

}