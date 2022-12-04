<?php

namespace Bassa\Php2\Blog\Http\Actions\Comment;

//POST http://127.0.0.1:8000/posts/comment
//{
//  "author_uuid": "<UUID>",
//"post_uuid": "<UUID>",
//"text": "<TEXT>",
//}

// тестовые данные:
// 1. uuid поста* 2. Текст коммента* 3. uuid_author не обязательно

use Bassa\Php2\Blog\Comment;
use Bassa\Php2\Blog\Exceptions\HttpException;
use Bassa\Php2\Blog\Exceptions\PostNotFoundException;
use Bassa\Php2\Blog\Exceptions\UserNotFoundException;
use Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException;
use Bassa\Php2\Blog\Http\ErrorResponse;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\Response;
use Bassa\Php2\Blog\Http\SuccessfulResponse;
use Bassa\Php2\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Bassa\Php2\Blog\UUID;
use Bassa\Php2\Blog\Http\Actions\ActionInterface;
use Psr\Log\LoggerInterface;

class CreateComment implements ActionInterface {

  /**
   * @param \Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface $postsRepository
   * @param \Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface $usersRepository
   * @param \Bassa\Php2\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface $commentsRepository
   * @param \Psr\Log\LoggerInterface $logger
   */
  public function __construct(
    private PostsRepositoryInterface    $postsRepository,
    private UsersRepositoryInterface    $usersRepository,
    private CommentsRepositoryInterface $commentsRepository,
    private LoggerInterface $logger
  ) {
  }

  public function handle(Request $request): Response {

    try {
      $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
      $postUuid = new UUID($request->jsonBodyField('post_uuid'));
    } catch (HttpException|InvalidArgumentException $e) {
      return new ErrorResponse($e->getMessage());
    }


    try {
      $author = $this->usersRepository->get($authorUuid);
    } catch (UserNotFoundException $e) {
      return new ErrorResponse($e->getMessage());
    }

    try {
      $post = $this->postsRepository->get($postUuid);
    } catch (PostNotFoundException $e) {
      return new ErrorResponse($e->getMessage());
    }
    $newCommentUuid = UUID::random();

    try {
      $comment = new Comment(
        $newCommentUuid,
        $author,
        $post,
        $request->jsonBodyField('text'),
      );
    } catch (HttpException $e) {
      return new ErrorResponse($e->getMessage());
    }

    $this->commentsRepository->save($comment);

    // Логируем UUID новой Коммента
    $this->logger->info("Comment created: $newCommentUuid");

    return new SuccessfulResponse(
      ['uuid' => (string) $newCommentUuid]
    );
  }


}