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
use Bassa\Php2\Blog\UUID;
use Bassa\Php2\Http\Auth\IdentificationInterface;
use Psr\Log\LoggerInterface;

class CreatePost implements ActionInterface {

  // Внедряем репозитории статей и пользователей

  /**
   * @param \Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface $postsRepository
   * @param \Bassa\Php2\Http\Auth\IdentificationInterface $identification
   * @param \Psr\Log\LoggerInterface $logger
   */
  public function __construct(
    private PostsRepositoryInterface $postsRepository,
    //    private UsersRepositoryInterface $usersRepository,
    // Вместо контракта репозитория пользователей
    // внедряем контракт идентификации
    private IdentificationInterface $identification
  ) {
  }

  /**
   * @param \Bassa\Php2\Blog\Http\Request $request
   *
   * @return \Bassa\Php2\Blog\Http\Response
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   */
  public function handle(Request $request): Response {
    // Идентифицируем пользователя -
    // автора статьи
    $author = $this->identification->user($request);

    $newPostUuid = UUID::random();

    try {
      /** @var object $author */
      $post = new Post(
        $newPostUuid,
        $author->uuid(),
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