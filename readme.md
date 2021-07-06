docker-compose up -d

тправка призов на счет
php artisan money:send  

получение токена
PUT http://localhost/api/get-api-token
Content-Type: application/json
{
"email" : "randy55@example.net",
"password" : "password"
}


получение приза
PUT http://localhost/api/get-prize
Content-Type: application/json
Authorization: Bearer ICjxUsaAsuLr4zqfRbPr9IdmN3ORjxKu3WudN82L18tHlPN7J25ZklJrJLPioxZ6uhbhacKFol4HyGuN

