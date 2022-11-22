<?php

namespace Bassa\Php2\Blog\Http\Actions\Post;

use Bassa\Php2\Blog\Http\Actions\ActionInterface;
use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\Response;

class DeletePost implements ActionInterface {

  /**
   * @param \Bassa\Php2\Blog\Http\Request $request
   *
   * @return \Bassa\Php2\Blog\Http\Response
   */
  public function handle(Request $request): Response;

}