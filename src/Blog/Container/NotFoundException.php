<?php

namespace Bassa\Php2\Blog\Container;

use Bassa\Php2\Blog\Exceptions\AppException;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends AppException implements NotFoundExceptionInterface {
}