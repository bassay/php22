<?php

namespace Bassa\Php2\Blog;
use Bassa\Php2\Blog\User;
use Bassa\Php2\Blog\Post;

class Comment {
  /**
   * @param \Bassa\Php2\Blog\UUID $uuid
   * @param \Bassa\Php2\Blog\User $user
   * @param \Bassa\Php2\Blog\Post $post
   * @param string $text
   */
  public function __construct(
    private UUID   $uuid,
    private User   $user,
    private Post   $post,
    private string $text
  ) {
  }

  /**
   * @return \Bassa\Php2\Blog\UUID
   */
  public function getUuid(): UUID {
    return $this->uuid;
  }

  /**
   * @return \Bassa\Php2\Blog\User
   */
  public function getUser(): \Bassa\Php2\Blog\User {
    return $this->user;
  }

  /**
   * @param \Bassa\Php2\Blog\User $user
   */
  public function setUser(\Bassa\Php2\Blog\User $user): void {
    $this->user = $user;
  }

  /**
   * @return \Bassa\Php2\Blog\Post
   */
  public function getPost(): \Bassa\Php2\Blog\Post {
    return $this->post;
  }

  /**
   * @param \Bassa\Php2\Blog\Post $post
   */
  public function setPost(\Bassa\Php2\Blog\Post $post): void {
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
      $this->getUser() . " , Для поста: " . $this->getPost() . ", С тектом $this->text" .
      PHP_EOL;
  }

}