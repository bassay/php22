<?php

namespace Bassa\Php2\Blog\Repositories\LikesRepository;

use Bassa\Php2\Blog\Exceptions\LikeNotFound;
use Bassa\Php2\Blog\Like;
use Bassa\Php2\Blog\Post;
use Bassa\Php2\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Bassa\Php2\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\UUID;
use PDO;
use PDOStatement;

class SqliteLikesRepository implements LikesRepositoryInterface {


  public function __construct(private PDO $connection) {
  }

  /**
   * @param \Bassa\Php2\Blog\Like $like
   *
   * @return void
   */
  public function save(Like $like): void {
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

  /**
   * @param \Bassa\Php2\Blog\Repositories\LikesRepository\User $user
   * @param \Bassa\Php2\Blog\Repositories\LikesRepository\Post $post
   *
   * @return int
   */
  public function checkLikeFromUser(User $user, Post $post): int {
    $userUuid = (string) $user->uuid();
    $postUuid = (string) $post->uuid();

    $statement = $this->connection->prepare(
      'SELECT * FROM post_likes 
               WHERE author_uuid = :author_uuid
                AND post_uuid = :post_uuid'
    );
    $statement->execute([
      ':author_uuid' => $userUuid,
      ':post_uuid' => $postUuid,
      ]);

    $count = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (in_array(true, $count)){
      return 1;
    } else {
      // Сделал на всякий случай, так то отрабатывает исключения
      return 0;
    }

  }


  public function getByLikeUuid(UUID $uuid): Like {
    $statement = $this->connection->prepare(
      'SELECT * FROM post_likes WHERE uuid = :uuid'
    );
    $statement->execute([':uuid' => $uuid]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if (!is_array($result)) {
      throw new LikeNotFound("uuid like not found");
    }

    $user = new SqliteUsersRepository($this->connection);
    $user = $user->get(new UUID($result['author_uuid']));

    $post = new SqlitePostsRepository($this->connection);
    $post = $post->get(new UUID($result['post_uuid']));

    return new Like(
      new UUID($result['uuid']),
      $post,
      $user
    );
  }

}