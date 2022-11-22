<?php

namespace Bassa\Php2\Blog\Repositories\PostsRepository;

use Bassa\Php2\Blog\Exceptions\PostNotFoundException;
use Bassa\Php2\Blog\Post;
use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\UUID;
use Bassa\Php2\Person\Name;
use PDO;
use PDOStatement;

class SqlitePostsRepository implements PostsRepositoryInterface {

  /**
   * @param \PDO $connection
   */
  public function __construct(private PDO $connection) {
  }

  /**
   * @param \Bassa\Php2\Blog\Post $post
   *
   * @return void
   */
  public function save(Post $post): void {

    $statement = $this->connection->prepare(
      'INSERT INTO posts (uuid, author_uuid, title, text)
                VALUES (:uuid, :author_uuid, :title, :text)'
    );

    $statement->execute([
      ':uuid' => $post->uuid(),
      ':author_uuid' => $post->getUser()->uuid(),
      ':title' => $post->getTitle(),
      ':text' => $post->getText(),
    ]);

  }


  /**
   * @throws \Bassa\Php2\Blog\Exceptions\PostNotFoundException
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   */
  public function delete(UUID $postUuid): int {
    if ($this->get($postUuid)->uuid()) {
      $sql = 'DELETE FROM posts WHERE uuid = :uuid';
      $statement = $this->connection->prepare($sql);
      $statement->bindParam(':uuid' , $postUuid);
      $statement->execute();
      return 1;
    } else {
      return 0;
    }
  }

  /**
   * @param \Bassa\Php2\Blog\UUID $uuid
   *
   * @return \Bassa\Php2\Blog\Post
   * @throws \Bassa\Php2\Blog\Exceptions\PostNotFoundException
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   */
  public function get(UUID $uuid): Post {
    $statement = $this->connection->prepare(
      'SELECT * FROM posts WHERE uuid = :uuid'
    );
    $statement->execute([':uuid' => $uuid]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if (FALSE === $result) {
      throw new PostNotFoundException(
        "Cannot get POST: $uuid"
      );
    }

    return new Post(
      new UUID($result['uuid']),
      new User(
        new UUID($result['author_uuid']),
        new Name('test', 'Rus')),
      $result['title'],
      $result['text']);
  }

}