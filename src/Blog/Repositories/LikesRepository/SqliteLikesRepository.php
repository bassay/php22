<?php

namespace Bassa\Php2\Blog\Repositories\LikesRepository;

use Bassa\Php2\Blog\Like;
use Bassa\Php2\Blog\UUID;
use PDO;

class SqliteLikesRepository implements LikesRepositoryInterface {


  public function __construct(private PDO $connection) {
  }

  /**
   * @param \Bassa\Php2\Blog\Like $like
   *
   * @return void
   */
  public function save(Like $like):void{
    $statement = $this->connection->prepare(
      'INSERT INTO post_likes (uuid, author_uuid, post_uuid)
                VALUES (:uuid, :author_uuid, :post_uuid)'
    );
    $statement->execute([
      ':uuid' => $like->getUuid(),
      ':author_uuid' => $like->getAuthorUuid()->uuid(),
      ':post_uuid' => $like->getPostUuid()->uuid(),
    ]);
  }


  public function getByPostUuid(UUID $uuid): Like {

    return new Like();
  }

}