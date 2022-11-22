**запрос на создание коммента**

POST `http://php22/http.php/posts/comment`
Content-Type: application/x-www-form-urlencoded
Cookie: XDEBUG_SESSION=start

{
"author_uuid": "01c891be-61ff-4efc-9f58-f1470b65f57e",
"post_uuid": "2facc892-4fd8-4b4b-a21d-761b4b9d732f",
"text": "Привет Мир, это тестовый коммент через API GB"
}

** Тестовая машина у меня OpenServer, я просто не стал алиас домена делать, 
из за этого такое в роутах!