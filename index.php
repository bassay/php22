<?php

//namespace Some\Namespace;
//class One
//{
//}
//echo One::class;

echo "<h1>Привет мир!</h1>";

// Функция с двумя параметрами возвращает строку


//function someFunction(bool $one, int $two = 123,): string
//{
//  return $one . $two;
//}
//// Создаём объект рефлексии
//// Передаём ему имя интересующей нас функции
//$reflection = new ReflectionFunction('someFunction');
//// Получаем тип возвращаемого функцией значения
//echo $reflection->getReturnType()->getName() . "\n <br>";
////echo var_dump($reflection->getReturnType()->getName()) . "\n <br>";
//// Получаем параметры функции
//foreach ($reflection->getParameters() as $parameter) {
//  // Для каждого параметра функции
//  // получаем его имя и тип
//  echo $parameter->getName().'-> ['.$parameter->getType()->getName()."]\n <br>";
//}