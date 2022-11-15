<?php

namespace Bassa\Php2\Blog;

class Post {

  private UUID $uuid;
  private User $user;
  private string $title;
  private string $text;


  /**
   * @param \Bassa\Php2\Blog\UUID $uuid
   * @param \Bassa\Php2\Blog\User $user
   * @param string $title
   * @param string $text
   */
  public function __construct(UUID $uuid, User $user, string $title, string $text) {
    $this->uuid = $uuid;
    $this->title = $title;
    $this->user = $user;
    $this->text = $text;
  }

  /**
   * @return string
   */
  public function getTitle(): string {
    return $this->title;
  }

  /**
   * @param string $title
   */
  public function setTitle(string $title): void {
    $this->title = $title;
  }

  /**
   * @return \Bassa\Php2\Blog\UUID
   */
  public function uuid(): UUID {
    return $this->uuid;
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


  public function __toString() {
    return $this->user . ' пишет: ' . $this->text . PHP_EOL;
  }

}