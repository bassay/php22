<?php

namespace Bassa\Php2\Blog\Http\Actions\Like;

use Bassa\Php2\Blog\Exceptions\HttpException;
use Bassa\Php2\Blog\Exceptions\LikeNotFound;
use Bassa\Php2\Blog\Http\Actions\ActionInterface;
use Bassa\Php2\Blog\Http\ErrorResponse;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\Response;
use Bassa\Php2\Blog\Http\SuccessfulResponse;
use Bassa\Php2\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use Bassa\Php2\Blog\UUID;
use Psr\Log\LoggerInterface;

class FindLikeByUuid implements ActionInterface {

  /**
   * @param \Bassa\Php2\Blog\Repositories\LikesRepository\LikesRepositoryInterface $likesRepository
   */
  public function __construct(
    private LikesRepositoryInterface $likesRepository,
    private LoggerInterface $logger
  ) {
  }

  public function handle(Request $request): Response {
    try {
      $likeUuid = $request->query('uuid');
    } catch (HttpException $e) {
      $likeUuid = $request->query('uuid');
      $this->logger->warning("Invalid format UUID:  $likeUuid");
      return new ErrorResponse($e->getMessage());
    }

    try {
      $like = $this->likesRepository->getByLikeUuid(new UUID($likeUuid));
    } catch (LikeNotFound $e) {
      $this->logger->warning("Like::class - Object not found:  $likeUuid");
      return new ErrorResponse($e->getMessage());
    }

    return new SuccessfulResponse([
      'uuid' => $likeUuid,
      'post_uuid' => (string) $like->getPostUuid()->uuid(),
      'user_uuid' => (string) $like->getAuthorUuid()->uuid(),
    ]);
  }

}