<?php

namespace Bassa\Php2\Blog\Repositories\LikesRepository;

use Bassa\Php2\Blog\Like;
use Bassa\Php2\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Bassa\Php2\Blog\Repositories\UsersRepository\SqliteUsersRepository;
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

    $statement = $this->connection->prepare(
      'SELECT * FROM post_likes WHERE uuid = :uuid'
    );
    $statement->execute([':uuid' => $uuid]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // как правильно сделать не знаю, нужна подсказка
    $user = new SqliteUsersRepository($this->connection);
    $user = $user->get(new UUID($result['author_uuid']));

    $post = new SqlitePostsRepository($this->connection);
    $post = $post->get(new UUID($result['post_uuid']));



//    echo "<pre>"; var_dump(new UUID($result['uuid']));  echo "</pre>"; die();

    return new Like(
      new UUID($result['uuid']),
      $post,
      $user
    );
  }

}