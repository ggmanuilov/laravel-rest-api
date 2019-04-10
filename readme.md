### Развертывание
0.1. composer install

0.2. php artisan migrate

### ТЗ
1. Сделать на Laravel простое REST-api для сокращателя ссылок. CRUD ендпойнты нужны только для ссылок! Для создания ссылки на входе получаем URL(например https://yandex.ru/pogoda/moscow/details), в ответ выводим id и сокращенный урл(например 
21, http://yourdomain.com/{your_url_uid_here}). Сокращенный УРЛ должен быть как можно короче. 

1.1 При клике на сокращенный урл, пользователя должно редиректить на изначальный URL и этот переход сохранятся в таблицу статистики переходов(поля произвольные) в бд.

### API

GET    /api/short   - show all records

POST   /api/short   - create item

PUT    /api/short/:id - update item

DELETE /api/short/:id - remove item

### WEB

GET /resolve/%code% - see short attribute from create form


2. Есть БД(внизу) и SQL запрос к ней:
````
SELECT posts.* FROM users JOIN posts ON posts.user_id=users.id
 WHERE users.created_at >= '2010-01-01'
 ORDER BY posts.created_at DESC
 LIMIT 10
````
Нужно оптимизировать бд так, чтобы она, на ваш взгляд, выглядела оптимально и запрос выше выполнялся с максимальной скоростью. Прислать итоговый дамп БД(без данных).

До оптимизации:
````
1	SIMPLE	posts	ALL					15	Using temporary; Using filesort
1	SIMPLE	users	ALL					5	Using where; Using join buffer (flat, BNL join)
````
[2019-04-09 22:36:05] 2 rows retrieved starting from 1 in 41 ms (execution: 7 ms, fetching: 34 ms)

Добавляем индексы

````
alter table posts add constraint posts_pk primary key (id);
alter table users add constraint users_pk primary key (id);

create index posts_user_id on posts (user_id);
create index posts_created_at on posts (created_at);
create index users_created_at on users (created_at);
````
Смотрим план выполнения:
````
1	SIMPLE	users	index	PRIMARY,users_created_at	users_created_at	6		5	Using where; Using index; Using temporary; Using filesort
1	SIMPLE	posts	ref	post_user_id	post_user_id	5	short_link.users.id	1	Using index condition
````
[2019-04-09 22:42:24] 2 rows retrieved starting from 1 in 18 ms (execution: 4 ms, fetching: 14 ms)

Скриптов оптимизации нет в миграции.

### Оптимизация запросов - денормализация

##### 1. добавляем поле - дата регистации пользователя в посты
```` 
alter table posts add column user_created_at datetime;
````

##### 2. заполняем данные о пользователях ( если данных много, нужно делать скрипт )
````
update posts
join users on users.id = posts.user_id
set posts.user_created_at = users.created_at;
````

##### 3. переписываем запрос
````
SELECT posts.*
FROM posts
WHERE posts.user_created_at >= '2010-01-01'
ORDER BY posts.created_at DESC
LIMIT 10;
````

### Путь к исходным данным
````
/storage/dump.sql
````

### Путь к оптимизированным данным
````
/storage/dump-optimize.sql
````

