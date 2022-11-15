<?php

namespace Bassa\Php2\Person;

class Name {

  public function __construct(private string $first, private string $last
  ) {
  }

  /**
   * @return string
   */
  public function first(): string {
    return $this->first;
  }

  /**
   * @param string $first
   */
  public function setFirst(string $first): void {
    $this->first = $first;
  }

  /**
   * @return string
   */
  public function last(): string {
    return $this->last;
  }

  /**
   * @param string $last
   */
  public function setLast(string $last): void {
    $this->last = $last;
  }

  /**
   * @return string
   */
  public function __toString() {
    return $this->first . ' ' . $this->last;
  }

}