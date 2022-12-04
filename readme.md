Просмотр лайка 
GET http://php22/http.php/like/show?uuid=e227c462-ac8b-49e1-8790-6de938b873d6
return post_Uuid b author_uuid

Добавление лайка (*Content-Type: application/x-www-form-urlencoded)
POST http://php22/http.php/like/create
{
"author_uuid": "ffe924fd-53ba-4478-a948-f4710a8e1b92",
"post_uuid": "96d25031-41db-4c5e-a5da-3bd2fe39bc66"
}
*для OpenServer 


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