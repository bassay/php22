<?php

//DELETE http://127.0.0.1:8000/posts?uuid=<UUID>

namespace Bassa\Php2\Blog\Http\Actions\Post;

use Bassa\Php2\Blog\Http\Actions\ActionInterface;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\Response;
use Bassa\Php2\Blog\Http\SuccessfulResponse;
use Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Bassa\Php2\Blog\UUID;

class DeletePost implements ActionInterface {

  /**
   * @param \Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface $postsRepository
   */
  public function __construct(
    private PostsRepositoryInterface $postsRepository
  ) {
  }

  /**
   * @param \Bassa\Php2\Blog\Http\Request $request
   *
   * @return \Bassa\Php2\Blog\Http\Response
   */
  public function handle(Request $request): Response {
    $postUuid = $request->query('uuid');
    $this->postsRepository->delete(new UUID($postUuid));

    return new SuccessfulResponse([
      "uuid" => $postUuid,
      "status" => "delete",
    ]);

  }

}