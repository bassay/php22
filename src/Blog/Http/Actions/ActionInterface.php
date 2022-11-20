<?php

namespace Bassa\Php2\Blog\Http\Actions;

use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\Response;

interface ActionInterface {

  /**
   * @param \Bassa\Php2\Blog\Http\Request $request
   *
   * @return \Bassa\Php2\Blog\Http\Response
   */
  public function handle(Request $request): Response;

}