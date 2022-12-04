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

class FindByUuid implements ActionInterface {

  public function __construct(
    private PostsRepositoryInterface $postRepository
  ) {
  }

  public function handle(Request $request): Response {

    try {
      $postUuid = $request->query('uuid');
    } catch (HttpException $e) {
      $likeUuid = $request->query('uuid');
      return new ErrorResponse($e->getMessage());
    }

    try {
      $post = $this->postRepository->get(new UUID($postUuid));
    } catch (PostNotFoundException $e) {
      return new ErrorResponse($e->getMessage());
    }

    return new SuccessfulResponse([
      'title' => $post->getTitle(),
      'text' => $post->getText(),
    ]);
  }

}