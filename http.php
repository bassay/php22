<?php
//use Bassa\Php2\Blog\Http\Request;
//
//require_once __DIR__ . '/vendor/autoload.php';
//
//// Создаём объект запроса из суперглобальных переменных
//$request = new Request($_GET, $_SERVER);
//
//// Получаем данные из объекта запроса
//$parameter = $request->query('some_parameter');
//$header = $request->header('Some-Header');
//$path = $request->path();
//
//echo 'Hello from PHP';


use Bassa\Php2\Blog\Http\Request;
use Bassa\Php2\Blog\Http\SuccessfulResponse;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER);

$parameter = $request->query('some_parameter');
$header = $request->header('Some-Header');
$path = $request->path();

// Создаём объект ответа
$response = new SuccessfulResponse([
  'message' => 'Hello from PHP',
]);
// Отправляем ответ
$response->send();