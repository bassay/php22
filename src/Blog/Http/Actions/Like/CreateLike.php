<?php
namespace Bassa\Php2\Blog\Http\Actions\Like;

use Bassa\Php2\Blog\Exceptions\HttpException;
use Bassa\Php2\Blog\Exceptions\PostNotFoundException;
use Bassa\Php2\Blog\Exceptions\UserNotFoundException;
use Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException;
use Bassa\Php2\Blog\Http\Actions\ActionInterface;
use Bassa\Php2\Blog\Http\ErrorResponse;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\Response;
use Bassa\Php2\Blog\Http\SuccessfulResponse;
use Bassa\Php2\Blog\Like;
use Bassa\Php2\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Bassa\Php2\Blog\UUID;

class CreateLike implements ActionInterface {

  /**
   * @param \Bassa\Php2\Blog\Repositories\PostsRepository\PostsRepositoryInterface $postsRepository
   * @param \Bassa\Php2\Blog\Repositories\UsersRepository\UsersRepositoryInterface $usersRepository
   * @param \Bassa\Php2\Blog\Repositories\LikesRepository\LikesRepositoryInterface $likesRepository
   */
  public function __construct(
    private PostsRepositoryInterface    $postsRepository,
    private UsersRepositoryInterface    $usersRepository,
    private LikesRepositoryInterface    $likesRepository
  ) {
  }


  /**
   * @param \Bassa\Php2\Blog\Http\Request $request
   *
   * @return \Bassa\Php2\Blog\Http\Response
   */
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
    // выше дублирование кода, пока не могу понять как быть. Это копия экшина
    // Коммента

    $res = $this->likesRepository->checkLikeFromUser(
      user: $author,
      post: $post
    );
    // Проверка на Установленный лайк Пост + Юзер
    if ($res == 1 ) {
      return new ErrorResponse("like is already installed");
    }

    $newLikeUuid = UUID::random();

    try {
      $like = new Like(
        uuid: $newLikeUuid,
        post_uuid: $post,
        author_uuid: $author,
      );
    } catch (HttpException $e) {
      return new ErrorResponse($e->getMessage());
    }
    $this->likesRepository->save($like);

    return new SuccessfulResponse(
      ['uuid' => (string) $newLikeUuid]
    );
  }
}