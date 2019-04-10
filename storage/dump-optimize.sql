-- auto-generated definition
create table posts
(
    id              int unsigned not null primary key,
    user_id         int          null,
    title           text         null,
    body            longtext     null,
    created_at      datetime     null,
    updated_at      datetime     null,
    user_created_at datetime     null
);

create index post_user_id on posts (user_id);

-- auto-generated definition
create table users
(
    id         int unsigned not null primary key,
    name       varchar(100) null,
    email      varchar(100) null comment 'user unique identifier in the system',
    is_active  int          null comment 'can be only 0 - false or 1 - true, default true',
    created_at datetime     null
);

create index users_created_at on users (created_at);


INSERT INTO users (id, name, email, is_active, created_at) VALUES (1, 'Mike', 'e1@mail.com', 1, '2019-04-04 09:00:56');
INSERT INTO users (id, name, email, is_active, created_at) VALUES (2, 'Paul', 'e2@mail.com', 1, '2019-01-04 09:00:56');
INSERT INTO users (id, name, email, is_active, created_at) VALUES (3, 'Alex', 'e3@mail.com', 1, '2019-04-04 09:00:56');
INSERT INTO users (id, name, email, is_active, created_at) VALUES (4, 'John', 'e4@mail.com', 0, '2019-04-04 09:00:56');
INSERT INTO users (id, name, email, is_active, created_at) VALUES (5, 'Anny', 'e5@mail.com', 0, '2019-04-04 09:00:56');

INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (1, 1, 'title1', 'body1', '2019-04-04 05:52:15', '2019-04-04 05:52:21', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (2, 1, 'title2', 'body2', '2019-04-04 05:53:25', '2019-04-04 05:53:28', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (3, 1, 'title3', 'body3', '2019-04-04 05:54:25', '2019-04-04 05:54:29', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (4, 2, 'title1', 'body1', '2019-04-04 05:52:15', '2019-04-04 05:52:21', '2019-01-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (5, 2, 'title2', 'body2', '2019-04-04 05:53:25', '2019-04-04 05:53:28', '2019-01-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (6, 2, 'title3', 'body3', '2019-04-04 05:54:25', '2019-04-04 05:54:29', '2019-01-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (7, 3, 'title1', 'body1', '2019-04-04 05:52:15', '2019-04-04 05:52:21', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (8, 3, 'title2', 'body2', '2019-04-04 05:53:25', '2019-04-04 05:53:28', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (9, 3, 'title3', 'body3', '2019-04-04 05:54:25', '2019-04-04 05:54:29', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (10, 4, 'title1', 'body1', '2019-04-04 05:52:15', '2019-04-04 05:52:21', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (11, 4, 'title2', 'body2', '2019-04-04 05:53:25', '2019-04-04 05:53:28', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (12, 4, 'title3', 'body3', '2019-04-04 05:54:25', '2019-04-04 05:54:29', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (13, 5, 'title1', 'body1', '2019-04-04 05:52:15', '2019-04-04 05:52:21', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (14, 5, 'title2', 'body2', '2019-04-04 05:53:25', '2019-04-04 05:53:28', '2019-04-04 09:00:56');
INSERT INTO posts (id, user_id, title, body, created_at, updated_at, user_created_at) VALUES (15, 5, 'title3', 'body3', '2019-04-04 05:54:25', '2019-04-04 05:54:29', '2019-04-04 09:00:56');