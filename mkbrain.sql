-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Фев 05 2022 г., 14:58
-- Версия сервера: 10.4.17-MariaDB
-- Версия PHP: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mkbrain`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(10) UNSIGNED NOT NULL,
  `answer_text` text NOT NULL,
  `answer_ques_id` int(10) UNSIGNED NOT NULL,
  `answer_correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------



--
-- Структура таблицы `connects`
--

CREATE TABLE `connects` (
  `connect_id` int(10) UNSIGNED NOT NULL,
  `connect_user_id` int(10) UNSIGNED NOT NULL,
  `connect_token` char(32) NOT NULL,
  `connect_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(10) UNSIGNED NOT NULL,
  `favorite_user_id` int(10) UNSIGNED DEFAULT NULL,
  `favorite_test_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `friends`
--

CREATE TABLE `friends` (
  `friend_id` int(10) UNSIGNED NOT NULL,
  `friend_sender_id` int(10) UNSIGNED DEFAULT NULL,
  `friend_recipient_id` int(10) UNSIGNED DEFAULT NULL,
  `friend_status_id` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `genders`
--

CREATE TABLE `genders` (
  `gender_id` tinyint(1) UNSIGNED NOT NULL,
  `gender_name` varchar(20) NOT NULL,
  `gender_short_name` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `genders`
--

INSERT INTO `genders` (`gender_id`, `gender_name`, `gender_short_name`) VALUES
(1, 'Женский', 'Ж'),
(2, 'Мужской', 'М');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_user_id` int(10) UNSIGNED NOT NULL,
  `group_describe` varchar(255) DEFAULT NULL,
  `group_img` varchar(255) DEFAULT NULL,
  `group_code` char(8) DEFAULT NULL,
  `group_organization_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `likes`
--

CREATE TABLE `likes` (
  `like_id` int(10) UNSIGNED NOT NULL,
  `like_user_id` int(10) UNSIGNED DEFAULT NULL,
  `like_test_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `marks`
--

CREATE TABLE `marks` (
  `mark_id` int(10) UNSIGNED NOT NULL,
  `mark_user_id` int(10) UNSIGNED NOT NULL,
  `mark_value` int(1) UNSIGNED DEFAULT NULL,
  `mark_test_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(10) UNSIGNED NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `menu_access` tinyint(2) NOT NULL,
  `menu_favicon` varchar(255) NOT NULL,
  `menu_link` varchar(255) NOT NULL,
  `menu_tooltip` varchar(255) NOT NULL,
  `menu_active` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_name`, `menu_access`, `menu_favicon`, `menu_link`, `menu_tooltip`, `menu_active`) VALUES
(1, 'Главная', -1, 'fas fa-home', '/mkbrain/', 'Главная', 1),
(2, 'Обратная связь', -1, 'fa fa-rss', '/mkbrain/error', 'Связаться', 0),
(3, 'Мой паспорт', 0, 'fas fa-user', '/mkbrain/my', 'Аккаунт', 1),
(4, 'Известия', 0, 'fa fa-newspaper-o', '/mkbrain/', 'Новости', 0),
(5, 'Письма', 0, 'fas fa-comments', '/mkbrain/im', 'Сообщения', 1),
(6, 'Знакомцы', 0, 'fas fa-users', '/mkbrain/friends', 'Друзья', 1),
(7, 'Мои классы', 0, 'fa fa-users', '/mkbrain/groups', 'Классы', 0),
(8, 'Тесты', 0, 'fas fa-chalkboard-teacher', '/mkbrain/tests', 'Тесты', 1),
(9, 'Конструктор тестов', 0, 'fa fa-wrench', '/mkbrain/constructor', 'Конструктор', 1),
(10, 'Мои попытки', 0, 'fa fa-table', '/mkbrain/marks', 'Оценки', 1),
(11, 'Мини курсы', 0, 'fa fa-graduation-cap', '/mkbrain/courses', 'Курсы', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(10) UNSIGNED NOT NULL,
  `msg_sender_id` int(10) UNSIGNED NOT NULL,
  `msg_recipient_id` int(10) UNSIGNED NOT NULL,
  `msg` text DEFAULT NULL,
  `msg_dos` datetime NOT NULL,
  `msg_status` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `methods`
--

CREATE TABLE `methods` (
  `method_id` tinyint(1) UNSIGNED NOT NULL,
  `method_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `methods`
--

INSERT INTO `methods` (`method_id`, `method_name`) VALUES
(1, 'Первая попытка'),
(2, 'Средняя оценка'),
(3, 'Последняя попытка');

-- --------------------------------------------------------

--
-- Структура таблицы `organizations`
--

CREATE TABLE `organizations` (
  `organization_id` int(10) UNSIGNED NOT NULL,
  `organization_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `organizations`
--

INSERT INTO `organizations` (`organization_id`, `organization_name`) VALUES
(1, 'MKStudio academy');

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `ques_id` int(10) UNSIGNED NOT NULL,
  `ques_text` varchar(2000) NOT NULL,
  `ques_test_id` int(10) UNSIGNED NOT NULL,
  `ques_type` tinyint(2) NOT NULL,
  `ques_score` int(2) UNSIGNED NOT NULL,
  `ques_ans_type` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `records`
--

CREATE TABLE `records` (
  `record_id` int(10) UNSIGNED NOT NULL,
  `record_group_id` int(10) UNSIGNED NOT NULL,
  `record_user_id` int(10) UNSIGNED NOT NULL,
  `record_type_id` tinyint(2) UNSIGNED DEFAULT NULL,
  `record_date` datetime DEFAULT NULL,
  `record_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `records_img`
--

CREATE TABLE `records_img` (
  `record_img_id` int(10) UNSIGNED NOT NULL,
  `record_img_record_id` int(10) UNSIGNED NOT NULL,
  `record_img_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `requests`
--

CREATE TABLE `requests` (
  `request_id` int(10) UNSIGNED NOT NULL,
  `request_user_id` int(10) UNSIGNED NOT NULL,
  `request_group_id` int(10) UNSIGNED NOT NULL,
  `request_status_id` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `role_id` tinyint(1) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Свободный пользователь'),
(2, 'Ученик'),
(3, 'Учитель'),
(4, 'Директор организации');

-- --------------------------------------------------------

--
-- Структура таблицы `statuses`
--

CREATE TABLE `statuses` (
  `status_id` tinyint(1) UNSIGNED NOT NULL,
  `status_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `statuses`
--

INSERT INTO `statuses` (`status_id`, `status_name`) VALUES
(1, 'notverified'),
(2, 'verified');

-- --------------------------------------------------------

--
-- Структура таблицы `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(3) UNSIGNED NOT NULL,
  `subject_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`) VALUES
(1, 'Другое'),
(2, 'Математика\r\n'),
(3, 'Развлекательный'),
(4, 'На общую эрудицию'),
(5, 'Гуманитарный'),
(6, 'Технический');

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE `tests` (
  `test_id` int(10) UNSIGNED NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `test_subject_id` int(3) UNSIGNED NOT NULL,
  `test_describe` text DEFAULT NULL,
  `test_attempts` tinyint(2) UNSIGNED DEFAULT NULL,
  `test_show_ans` tinyint(1) UNSIGNED DEFAULT NULL,
  `test_code` char(8) NOT NULL,
  `test_privacy` tinyint(1) UNSIGNED DEFAULT NULL,
  `test_method_id` tinyint(1) UNSIGNED NOT NULL,
  `test_user_id` int(10) UNSIGNED NOT NULL,
  `test_toe` int(10) UNSIGNED NOT NULL,
  `test_img` varchar(256) DEFAULT NULL,
  `test_likes` int(11) NOT NULL,
  `test_cnt` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `test_status`
--

CREATE TABLE `test_status` (
  `test_status_id` int(10) UNSIGNED NOT NULL,
  `test_status_user_id` int(10) UNSIGNED NOT NULL,
  `test_status_test_id` int(10) UNSIGNED NOT NULL,
  `test_status_doe` int(100) NOT NULL DEFAULT current_timestamp(),
  `test_status_is_completed` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--

CREATE TABLE `types` (
  `type_id` tinyint(2) UNSIGNED NOT NULL,
  `type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `types`
--

INSERT INTO `types` (`type_id`, `type_name`) VALUES
(1, 'Мероприятие '),
(2, 'Домашняя работа'),
(3, 'Предупреждение'),
(4, 'Оценки'),
(5, 'Обычная запись ');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_surname` varchar(255) NOT NULL,
  `user_password` char(32) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_dob` date DEFAULT NULL,
  `user_status_id` tinyint(1) UNSIGNED DEFAULT NULL,
  `user_gender_id` tinyint(1) UNSIGNED DEFAULT NULL,
  `user_role_id` tinyint(1) UNSIGNED DEFAULT NULL,
  `user_img` varchar(255) DEFAULT NULL,
  `user_code` int(6) DEFAULT NULL,
  `user_describe` varchar(255) DEFAULT NULL,
  `user_organization_id` int(11) UNSIGNED DEFAULT NULL,
  `user_privacy` int(1) UNSIGNED NOT NULL,
  `user_vk_id` int(11) UNSIGNED NOT NULL,
  `user_unique` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `answer_ques_id` (`answer_ques_id`);


--
-- Индексы таблицы `connects`
--
ALTER TABLE `connects`
  ADD PRIMARY KEY (`connect_id`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `favorite_user_id` (`favorite_user_id`),
  ADD KEY `favorite_test_id` (`favorite_test_id`);

--
-- Индексы таблицы `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`friend_id`),
  ADD KEY `friend_sender_id` (`friend_sender_id`),
  ADD KEY `friend_recipient_id` (`friend_recipient_id`),
  ADD KEY `friend_status_id` (`friend_status_id`);

--
-- Индексы таблицы `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`gender_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `group_name` (`group_name`),
  ADD KEY `group_user_id` (`group_user_id`),
  ADD KEY `group_organization_id` (`group_organization_id`);

--
-- Индексы таблицы `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `like_user_id` (`like_user_id`),
  ADD KEY `like_test_id` (`like_test_id`);

--
-- Индексы таблицы `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`mark_id`),
  ADD KEY `mark_user_id` (`mark_user_id`);

--
-- Индексы таблицы `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `msg_sender_id` (`msg_sender_id`),
  ADD KEY `msg_recipient_id` (`msg_recipient_id`);

--
-- Индексы таблицы `methods`
--
ALTER TABLE `methods`
  ADD PRIMARY KEY (`method_id`);

--
-- Индексы таблицы `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`organization_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`ques_id`),
  ADD KEY `ques_test_id` (`ques_test_id`);

--
-- Индексы таблицы `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `record_group_id` (`record_group_id`),
  ADD KEY `record_user_id` (`record_user_id`),
  ADD KEY `record_type_id` (`record_type_id`);

--
-- Индексы таблицы `records_img`
--
ALTER TABLE `records_img`
  ADD PRIMARY KEY (`record_img_id`),
  ADD KEY `img_record_id` (`record_img_record_id`);

--
-- Индексы таблицы `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `request_user_id` (`request_user_id`),
  ADD KEY `request_group_id` (`request_group_id`),
  ADD KEY `request_status_id` (`request_status_id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Индексы таблицы `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`status_id`);

--
-- Индексы таблицы `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Индексы таблицы `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_id`),
  ADD KEY `test_user_id` (`test_user_id`),
  ADD KEY `test_method_id` (`test_method_id`),
  ADD KEY `test_subject_id` (`test_subject_id`);

--
-- Индексы таблицы `test_status`
--
ALTER TABLE `test_status`
  ADD PRIMARY KEY (`test_status_id`);

--
-- Индексы таблицы `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`type_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_gender_id` (`user_gender_id`),
  ADD KEY `user_status_id` (`user_status_id`),
  ADD KEY `user_role_id` (`user_role_id`),
  ADD KEY `user_organization_id` (`user_organization_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `connects`
--
ALTER TABLE `connects`
  MODIFY `connect_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `friends`
--
ALTER TABLE `friends`
  MODIFY `friend_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `genders`
--
ALTER TABLE `genders`
  MODIFY `gender_id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `marks`
--
ALTER TABLE `marks`
  MODIFY `mark_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `methods`
--
ALTER TABLE `methods`
  MODIFY `method_id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `organizations`
--
ALTER TABLE `organizations`
  MODIFY `organization_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `ques_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `records`
--
ALTER TABLE `records`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `records_img`
--
ALTER TABLE `records_img`
  MODIFY `record_img_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `statuses`
--
ALTER TABLE `statuses`
  MODIFY `status_id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `tests`
--
ALTER TABLE `tests`
  MODIFY `test_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `test_status`
--
ALTER TABLE `test_status`
  MODIFY `test_status_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `types`
--
ALTER TABLE `types`
  MODIFY `type_id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`answer_ques_id`) REFERENCES `questions` (`ques_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`favorite_test_id`) REFERENCES `tests` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`favorite_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`group_organization_id`) REFERENCES `organizations` (`organization_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_ibfk_2` FOREIGN KEY (`group_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
