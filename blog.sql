use blog;

create table categories
(
    cat_id int auto_increment
        primary key,
    name   varchar(100) not null,
    constraint categories_name_uindex
        unique (name)
);

create table users
(
    id     int auto_increment
        primary key,
    name   varchar(100)         null,
    email  varchar(255)         null,
    gender tinyint(1) default 0 null,
    constraint users_email_uindex
        unique (email),
    constraint users_name_uindex
        unique (name)
);

create table posts
(
    id          int auto_increment
        primary key,
    user_id     int          not null,
    category_id int          not null,
    text        varchar(255) null,
    constraint posts_categories_cat_id_fk
        foreign key (category_id) references categories (cat_id)
            on update cascade on delete cascade,
    constraint posts_users_id_fk
        foreign key (user_id) references users (id)
            on update cascade on delete cascade
);

insert into users values
    (1, 'vasya pupkin', 'vasyapupkin@gmail.ru', 0),
    (2, 'petya vasechkin', 'petyavasechkin@gmail.ru', 0),
    (3, 'ihor dudikoff', 'ihordudikoff@gmail.ru', 0),
    (4, 'lora palmer', 'lorapalmer@gmail.ru', 1);

insert into categories values
    (1, 'php'),
    (2, 'java'),
    (3, 'mysql'),
    (4, 'laravel');

insert into posts values
    (1, 1, 1, 'text1'),
    (2, 1, 3, 'text2'),
    (3, 2, 4, 'text3'),
    (4, 2, 3, 'text4'),
    (5, 3, 1, 'text5'),
    (6, 4, 1, 'text6');