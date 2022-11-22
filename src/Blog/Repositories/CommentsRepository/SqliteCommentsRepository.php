<?php

namespace Bassa\Php2\Blog\Repositories\CommentsRepository;

use Bassa\Php2\Blog\Comment;
use Bassa\Php2\Blog\Post;
use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\UUID;
use Bassa\Php2\Person\Name;
use PDO;

class SqliteCommentsRepository implements CommentsRepositoryInterface {
  public function __construct(private PDO $connection) {
  }


  /**
   * @param \Bassa\Php2\Blog\Comment $comment
   *
   * @return void
   */
  public function save(Comment $comment): void {
    $statement = $this->connection->prepare(
      'INSERT INTO comments (uuid, author_uuid, post_uuid, text)
                VALUES (:uuid, :author_uuid, :post_uuid, :text)'
    );

    $statement->execute([
      ':uuid' => $comment->getUuid(),
      ':author_uuid' => $comment->getUser()->uuid(),
      ':post_uuid' => $comment->getPost()->uuid(),
      ':text' => $comment->getText(),
    ]);
  }

  /**
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   * @throws \Bassa\Php2\Blog\Repositories\CommentsRepository\CommentNotFoundException
   */
  public function get(UUID $uuid): Comment {
    $statement = $this->connection->prepare(
      'SELECT * FROM comments WHERE uuid = :uuid'
    );
    $statement->execute([':uuid' => $uuid]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if (FALSE === $result) {
      throw new CommentNotFoundException(
        "Cannot get Comment: $uuid"
      );
    }

    $user = new User(
      new UUID($result['author_uuid']),
      new Name('test', 'Rus'));


    return new Comment(
      new UUID($result['uuid']),
      $user,
      new Post(
        uuid: new UUID($result['post_uuid']),
        user: $user,
        title: 'test title',
        text: 'test text post'
      ),
      $result['text']
    );
  }
}