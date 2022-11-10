<?php

namespace Bassa\Php2\Blog;

use Bassa\Php2\Person\Person;

class Post
{
    public function __construct(private Person $author, private string $text)
    {
    }

    public function __toString()
    {
        return $this->author . ' пишет: ' . $this->text;
    }
}