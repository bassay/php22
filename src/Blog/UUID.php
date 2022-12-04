<?php

namespace Bassa\Php2\Blog;

use Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException;

class UUID {
  /**
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   */
  public function __construct(
    private string $uuidString
  ) {
    // Если входная строка не подходит по формату -
    // бросаем исключение InvalidArgumentException
    // (его мы тоже добавили)
    //
    // Таким образом, мы гарантируем, что если объект
    // был создан, то он точно содержит правильный UUID
    if (!uuid_is_valid($uuidString)) {
      throw new InvalidArgumentException("Malformed UUID: $this->uuidString");
    }
  }

  // А так мы можем сгенерировать новый случайный UUID

  /**
   * @throws \Bassa\Php2\Blog\Exceptions\UUID\InvalidArgumentException
   */
  public static function random(): self {
    return new self(uuid_create(UUID_TYPE_RANDOM));
  }

  /**
   * @return string
   */
  public function __toString(): string {
    return $this->uuidString;
  }

}