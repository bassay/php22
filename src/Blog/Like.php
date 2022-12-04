<?php

namespace Bassa\Php2\Blog;

class Like {

  public function __construct(
    private UUID $uuid,
    private Post $post_uuid,
    private User $author_uuid
  ) {
  }

  /**
   * @return \Bassa\Php2\Blog\UUID
   */
  public function getUuid(): UUID {
    return $this->uuid;
  }

  /**
   * @param \Bassa\Php2\Blog\UUID $uuid
   */
  public function setUuid(UUID $uuid): void {
    $this->uuid = $uuid;
  }

  /**
   * @return \Bassa\Php2\Blog\Post
   */
  public function getPostUuid(): Post {
    return $this->post_uuid;
  }

  /**
   * @param \Bassa\Php2\Blog\Post $post_uuid
   */
  public function setPostUuid(Post $post_uuid): void {
    $this->post_uuid = $post_uuid;
  }

  /**
   * @return \Bassa\Php2\Blog\User
   */
  public function getAuthorUuid(): User {
    return $this->author_uuid;
  }

  /**
   * @param \Bassa\Php2\Blog\User $author_uuid
   */
  public function setAuthorUuid(User $author_uuid): void {
    $this->author_uuid = $author_uuid;
  }

  public function __toString(): string {
    return "для отладки: uuid: $this->uuid -> author_uuid: $this->author_uuid 
    -> post_uuid: $this->post_uuid " . PHP_EOL;
  }

}