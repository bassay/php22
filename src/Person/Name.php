<?php

namespace Bassa\Php2\Person;

class Name {

  public function __construct(private string $firstName, private string $lastName
  ) {
  }

  /**
   * @return string
   */
  public function getFirstName(): string {
    return $this->firstName;
  }

  /**
   * @param string $firstName
   */
  public function setFirstName(string $firstName): void {
    $this->firstName = $firstName;
  }

  /**
   * @return string
   */
  public function getLastName(): string {
    return $this->lastName;
  }

  /**
   * @param string $lastName
   */
  public function setLastName(string $lastName): void {
    $this->lastName = $lastName;
  }


  public function __toString() {
    return $this->firstName . ' ' . $this->lastName;
  }

}