<?php

namespace Bassa\Php2\Blog\Http\Actions\Post;


use Bassa\Php2\Blog\Exceptions\HttpException;
use Bassa\Php2\Blog\Exceptions\PostNotFoundException;
use Bassa\Php2\Blog\Http\Actions\ActionInterface;
use Bassa\Php2\Blog\Http\ErrorResponse;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\Response;
use Bassa\Php2\Blog\Http\SuccessfulResponse;
use Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Bassa\Php2\Blog\UUID;
use Psr\Log\LoggerInterface;

class FindByUuid implements ActionInterface {

  public function __construct(
    private PostsRepositoryInterface $postRepository,
    private LoggerInterface $logger
  ) {
  }

  public function handle(Request $request): Response {

    try {
      $postUuid = $request->query('uuid');
    } catch (HttpException $e) {
      $likeUuid = $request->query('uuid');
      $this->logger->warning("Invalid format UUID:  $likeUuid");
      return new ErrorResponse($e->getMessage());
    }

    try {
      $post = $this->postRepository->get(new UUID($postUuid));
    } catch (PostNotFoundException $e) {
      $this->logger->warning("Post::class - Object not found:  $postUuid");
      return new ErrorResponse($e->getMessage());
    }

    return new SuccessfulResponse([
      'title' => $post->getTitle(),
      'text' => $post->getText(),
    ]);
  }

}