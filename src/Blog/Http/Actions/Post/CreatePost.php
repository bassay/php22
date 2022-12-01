<?php

namespace Bassa\Php2\Blog\Http\Actions\Post;


use Bassa\Php2\Blog\Exceptions\HttpException;
use Bassa\Php2\Blog\Exceptions\UserNotFoundException;
use Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException;
use Bassa\Php2\Blog\Http\Actions\ActionInterface;
use Bassa\Php2\Blog\Http\ErrorResponse;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\Response;
use Bassa\Php2\Blog\Http\SuccessfulResponse;
use Bassa\Php2\Blog\Post;
use Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Bassa\Php2\Blog\UUID;

class CreatePost implements ActionInterface {

  // Внедряем репозитории статей и пользователей
  public function __construct(
    private PostsRepositoryInterface $postsRepository,
    private UsersRepositoryInterface $usersRepository,
  ) {
  }

  /**
   * @param \Bassa\Php2\Blog\Http\Request $request
   *
   * @return \Bassa\Php2\Blog\Http\ErrorResponse|\Bassa\Php2\Blog\Http\SuccessfulResponse
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   */

  public function handle(Request $request): Response {
    try {
      $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
    } catch (HttpException|InvalidArgumentException $e) {
      return new ErrorResponse($e->getMessage());
    }

    try {
      $author = $this->usersRepository->get($authorUuid);
    } catch (UserNotFoundException $e) {
      return new ErrorResponse($e->getMessage());
    }
    $newPostUuid = UUID::random();
    try {
      $post = new Post(
        $newPostUuid,
        $author,
        $request->jsonBodyField('title'),
        $request->jsonBodyField('text'),
      );
    } catch (HttpException $e) {
      return new ErrorResponse($e->getMessage());
    }
    $this->postsRepository->save($post);
    return new SuccessfulResponse([
      'uuid' => (string) $newPostUuid,
    ]);
  }

}