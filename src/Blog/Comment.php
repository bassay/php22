<?php

namespace Bassa\Php2\Blog;
use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\Post;

class Comment {

  public function __construct(
    private int    $id,
    private User   $user,
    private Post   $post,
    private string $text
  ) {
    $this->id = $id;
    $this->user = $user;
    $this->post = $post;
    $this->text = $text;
  }

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId(int $id): void {
    $this->id = $id;
  }

  /**
   * @return \Bassa\Php2\Blog\User
   */
  public function getUser(): User {
    return $this->user;
  }

  /**
   * @param \Bassa\Php2\Blog\User $user
   */
  public function setUser(User $user): void {
    $this->user = $user;
  }

  /**
   * @return \Bassa\Php2\Blog\Post
   */
  public function getPost(): Post {
    return $this->post;
  }

  /**
   * @param \Bassa\Php2\Blog\Post $post
   */
  public function setPost(Post $post): void {
    $this->post = $post;
  }

  /**
   * @return string
   */
  public function getText(): string {
    return $this->text;
  }

  /**
   * @param string $text
   */
  public function setText(string $text): void {
    $this->text = $text;
  }


  public function __toString(): string {
    return "Коммент ID_comment: $this->id , User: " .
      $this->getUser
    () . " , Для поста: " . $this->getPost() . ", С тектом $this->text" .
      PHP_EOL;
  }

}