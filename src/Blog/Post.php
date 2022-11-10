<?php

namespace Bassa\Php2\Blog;

use Bassa\Php2\Blog\User;

class Post
{
  private int $id;
  private User $user;
  private string $text;

  /**
   * @param int $id
   * @param \Bassa\Php2\Blog\User $user
   * @param string $text
   */
  public function __construct(int $id, User $user, string $text) {
    $this->id = $id;
    $this->user = $user;
    $this->text = $text;
  }


  public function __toString()
    {
        return $this->user . ' пишет: ' . $this->text . PHP_EOL;
    }
}