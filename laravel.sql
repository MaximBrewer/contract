-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 07 2020 г., 12:28
-- Версия сервера: 5.7.28-0ubuntu0.16.04.2
-- Версия PHP: 7.2.26-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `laravel`
--

-- --------------------------------------------------------

--
-- Структура таблицы `contragents`
--

CREATE TABLE `contragents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `federal_district_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `is_online` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `rating` float(3,2) NOT NULL,
  `holding` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `inn` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `contragents`
--

INSERT INTO `contragents` (`id`, `title`, `federal_district_id`, `region_id`, `is_online`, `active`, `rating`, `holding`, `fio`, `created_at`, `updated_at`, `inn`, `legal_address`, `phone`) VALUES
(1, 'eee', 2, 72, 1, 0, 3.00, NULL, '12', NULL, '2020-01-06 16:01:46', '21212', NULL, NULL),
(2, 'Чернянский Мясокомбинат', 9, 54, 1, 1, 5.00, 'нет', 'василий Петрович Алехин', '2020-01-06 13:02:00', '2020-01-06 15:59:45', '3119000483', 'БЕЛГОРОДСКАЯ ОБЛ.,PП ЧЕРНЯНКА,ТУП МЯСОКОМБИНАТА, Д 6', '+79155238197');

-- --------------------------------------------------------

--
-- Структура таблицы `contragent_type`
--

CREATE TABLE `contragent_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `contragent_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `contragent_type`
--

INSERT INTO `contragent_type` (`id`, `contragent_id`, `type_id`) VALUES
(1, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `data_rows`
--

CREATE TABLE `data_rows` (
  `id` int(10) UNSIGNED NOT NULL,
  `data_type_id` int(10) UNSIGNED NOT NULL,
  `field` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `browse` tinyint(1) NOT NULL DEFAULT '1',
  `read` tinyint(1) NOT NULL DEFAULT '1',
  `edit` tinyint(1) NOT NULL DEFAULT '1',
  `add` tinyint(1) NOT NULL DEFAULT '1',
  `delete` tinyint(1) NOT NULL DEFAULT '1',
  `details` text COLLATE utf8mb4_unicode_ci,
  `order` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `data_rows`
--

INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES
(1, 1, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(2, 1, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, NULL, 2),
(3, 1, 'email', 'text', 'Email', 1, 1, 1, 1, 1, 1, NULL, 3),
(4, 1, 'password', 'password', 'Password', 1, 0, 0, 1, 1, 0, NULL, 4),
(5, 1, 'remember_token', 'text', 'Remember Token', 0, 0, 0, 0, 0, 0, NULL, 5),
(6, 1, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, NULL, 6),
(7, 1, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 7),
(8, 1, 'avatar', 'image', 'Avatar', 0, 1, 1, 1, 1, 1, NULL, 8),
(9, 1, 'user_belongsto_role_relationship', 'relationship', 'Role', 0, 1, 1, 1, 1, 0, '{"model":"TCG\\\\Voyager\\\\Models\\\\Role","table":"roles","type":"belongsTo","column":"role_id","key":"id","label":"display_name","pivot_table":"roles","pivot":0}', 10),
(10, 1, 'user_belongstomany_role_relationship', 'relationship', 'Roles', 0, 1, 1, 1, 1, 0, '{"model":"TCG\\\\Voyager\\\\Models\\\\Role","table":"roles","type":"belongsToMany","column":"id","key":"id","label":"display_name","pivot_table":"user_roles","pivot":"1","taggable":"0"}', 11),
(11, 1, 'settings', 'hidden', 'Settings', 0, 0, 0, 0, 0, 0, NULL, 12),
(12, 2, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(13, 2, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, NULL, 2),
(14, 2, 'created_at', 'timestamp', 'Created At', 0, 0, 0, 0, 0, 0, NULL, 3),
(15, 2, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 4),
(16, 3, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(17, 3, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, NULL, 2),
(18, 3, 'created_at', 'timestamp', 'Created At', 0, 0, 0, 0, 0, 0, NULL, 3),
(19, 3, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 4),
(20, 3, 'display_name', 'text', 'Display Name', 1, 1, 1, 1, 1, 1, NULL, 5),
(21, 1, 'role_id', 'text', 'Role', 1, 1, 1, 1, 1, 1, NULL, 9),
(56, 9, 'id', 'text', 'Id', 1, 0, 0, 0, 0, 0, '{}', 1),
(57, 9, 'title', 'text', 'Название', 1, 1, 1, 1, 1, 1, '{}', 2),
(60, 9, 'is_online', 'checkbox', 'Онлайн', 1, 1, 1, 1, 1, 1, '{"on":"\\u0414\\u0430","off":"\\u041d\\u0435\\u0442","checked":"false"}', 9),
(61, 9, 'active', 'checkbox', 'Активность', 1, 1, 1, 1, 1, 1, '{"on":"\\u0414\\u0430","off":"\\u041d\\u0435\\u0442","checked":"false"}', 10),
(62, 9, 'rating', 'number', 'Рейтинг', 1, 0, 0, 0, 0, 0, '{}', 12),
(63, 9, 'holding', 'text', 'Холдинг', 0, 1, 1, 1, 1, 1, '{}', 13),
(64, 9, 'fio', 'text', 'ФИО', 1, 1, 1, 1, 1, 1, '{}', 15),
(65, 9, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 1, 0, 1, '{}', 17),
(66, 9, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 18),
(67, 9, 'inn', 'number', 'ИНН', 1, 1, 1, 1, 1, 1, '{}', 11),
(68, 10, 'id', 'text', 'Id', 1, 0, 0, 0, 0, 0, '{}', 1),
(69, 10, 'title', 'text', 'Наименование', 0, 1, 1, 1, 1, 1, '{}', 2),
(70, 12, 'id', 'text', 'Id', 1, 0, 0, 0, 0, 0, '{}', 1),
(71, 12, 'federal_district_id', 'text', 'Федеральный округ', 0, 0, 1, 1, 1, 1, '{}', 3),
(72, 12, 'title', 'text', 'Наименование', 0, 1, 1, 1, 1, 1, '{}', 4),
(73, 12, 'region_belongsto_federal_district_relationship', 'relationship', 'Федеральный округ', 0, 1, 1, 1, 1, 1, '{"model":"\\\\App\\\\FederalDistrict","table":"federal_districts","type":"belongsTo","column":"federal_district_id","key":"id","label":"title","pivot_table":"contragents","pivot":"0","taggable":"0"}', 2),
(74, 10, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 1, 0, 1, '{}', 3),
(75, 10, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 4),
(76, 12, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 1, 0, 1, '{}', 4),
(77, 12, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 5),
(78, 9, 'contragent_belongsto_region_relationship', 'relationship', 'Область', 0, 1, 1, 1, 1, 1, '{"model":"\\\\App\\\\Region","table":"regions","type":"belongsTo","column":"region_id","key":"id","label":"title","pivot_table":"contragents","pivot":"0","taggable":"0"}', 4),
(79, 9, 'contragent_belongsto_federal_district_relationship', 'relationship', 'Федеральный округ', 0, 1, 1, 1, 1, 1, '{"model":"\\\\App\\\\FederalDistrict","table":"federal_districts","type":"belongsTo","column":"federal_district_id","key":"id","label":"title","pivot_table":"contragents","pivot":"0","taggable":"0"}', 3),
(80, 9, 'federal_district_id', 'text', 'Federal District Id', 1, 0, 1, 1, 1, 1, '{}', 7),
(81, 9, 'region_id', 'text', 'Region Id', 1, 0, 1, 1, 1, 1, '{}', 8),
(82, 9, 'contragent_belongstomany_type_relationship', 'relationship', 'Типы предприятия', 0, 1, 1, 1, 1, 1, '{"model":"\\\\App\\\\Type","table":"types","type":"belongsToMany","column":"id","key":"id","label":"title","pivot_table":"contragent_type","pivot":"1","taggable":"on"}', 5),
(83, 13, 'id', 'text', 'Id', 1, 0, 0, 0, 0, 0, '{}', 1),
(84, 13, 'title', 'text', 'Тип', 1, 1, 1, 1, 1, 1, '{}', 2),
(85, 9, 'legal_address', 'text', 'Юр. адрес', 0, 1, 1, 1, 1, 1, '{}', 14),
(86, 9, 'phone', 'text', 'Телефон', 0, 1, 1, 1, 1, 1, '{}', 16),
(87, 13, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 1, 0, 1, '{}', 3),
(88, 13, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 4),
(89, 14, 'id', 'text', 'Id', 1, 0, 0, 0, 0, 0, '{}', 1),
(90, 14, 'coords', 'text', 'Coords', 0, 1, 1, 1, 1, 1, '{}', 2),
(91, 14, 'address', 'text', 'Address', 0, 1, 1, 1, 1, 1, '{}', 3),
(92, 14, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 1, 0, 1, '{}', 4),
(93, 14, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 5),
(94, 9, 'contragent_hasmany_store_relationship', 'relationship', 'stores', 0, 1, 1, 1, 1, 1, '{"model":"\\\\App\\\\Store","table":"stores","type":"hasMany","column":"contragent_id","key":"id","label":"address","pivot_table":"contragent_type","pivot":"0","taggable":"0"}', 6),
(95, 14, 'contragent_id', 'text', 'Contragent Id', 1, 0, 1, 1, 1, 1, '{}', 6),
(96, 14, 'store_belongsto_contragent_relationship', 'relationship', 'contragents', 0, 1, 1, 1, 1, 1, '{"model":"\\\\App\\\\Contragent","table":"contragents","type":"belongsTo","column":"contragent_id","key":"id","label":"title","pivot_table":"contragent_type","pivot":"0","taggable":"0"}', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `data_types`
--

CREATE TABLE `data_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_singular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_plural` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `server_side` tinyint(4) NOT NULL DEFAULT '0',
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `data_types`
--

INSERT INTO `data_types` (`id`, `name`, `slug`, `display_name_singular`, `display_name_plural`, `icon`, `model_name`, `policy_name`, `controller`, `description`, `generate_permissions`, `server_side`, `details`, `created_at`, `updated_at`) VALUES
(1, 'users', 'users', 'User', 'Users', 'voyager-person', 'TCG\\Voyager\\Models\\User', 'TCG\\Voyager\\Policies\\UserPolicy', 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController', '', 1, 0, NULL, '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(2, 'menus', 'menus', 'Menu', 'Menus', 'voyager-list', 'TCG\\Voyager\\Models\\Menu', NULL, '', '', 1, 0, NULL, '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(3, 'roles', 'roles', 'Role', 'Roles', 'voyager-lock', 'TCG\\Voyager\\Models\\Role', NULL, '', '', 1, 0, NULL, '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(9, 'contragents', 'contragents', 'Contragent', 'Contragents', NULL, 'App\\Contragent', NULL, NULL, NULL, 1, 1, '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}', '2020-01-05 23:05:11', '2020-01-06 15:56:41'),
(10, 'federal_districts', 'federal-districts', 'Федеральный округ', 'Федеральные округа', NULL, 'App\\FederalDistrict', NULL, NULL, NULL, 1, 1, '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}', '2020-01-06 07:07:53', '2020-01-06 07:16:27'),
(12, 'regions', 'regions', 'Область', 'Области', NULL, 'App\\Region', NULL, NULL, NULL, 1, 1, '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}', '2020-01-06 07:11:06', '2020-01-06 10:16:21'),
(13, 'types', 'types', 'Типы предприятий', 'Тип предприятия', 'voyager-list', 'App\\Type', NULL, NULL, NULL, 1, 1, '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}', '2020-01-06 10:48:22', '2020-01-06 12:57:38'),
(14, 'stores', 'stores', 'Store', 'Stores', NULL, 'App\\Store', NULL, NULL, NULL, 1, 1, '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}', '2020-01-06 13:08:37', '2020-01-06 13:14:18');

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `federal_districts`
--

CREATE TABLE `federal_districts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `federal_districts`
--

INSERT INTO `federal_districts` (`id`, `title`, `created_at`, `updated_at`) VALUES
(2, 'ЦФО', '2020-01-06 07:19:02', '2020-01-06 07:19:02'),
(3, 'СЗФО', '2020-01-06 07:19:10', '2020-01-06 07:19:10'),
(4, 'ПФО', '2020-01-06 07:19:17', '2020-01-06 07:19:17'),
(5, 'ЮФО', '2020-01-06 07:19:23', '2020-01-06 07:19:23'),
(6, 'СФО', '2020-01-06 07:19:30', '2020-01-06 07:19:30'),
(7, 'ДФО', '2020-01-06 07:19:36', '2020-01-06 07:19:36'),
(8, 'СКФО', '2020-01-06 07:19:46', '2020-01-06 07:19:46'),
(9, 'УФО', '2020-01-06 14:15:12', '2020-01-06 14:15:12');

-- --------------------------------------------------------

--
-- Структура таблицы `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `menus`
--

INSERT INTO `menus` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2020-01-05 22:35:04', '2020-01-05 22:35:04');

-- --------------------------------------------------------

--
-- Структура таблицы `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES
(1, 1, 'Dashboard', '', '_self', 'voyager-boat', NULL, NULL, 6, '2020-01-05 22:35:04', '2020-01-06 13:14:50', 'voyager.dashboard', NULL),
(2, 1, 'Media', '', '_self', 'voyager-images', NULL, NULL, 8, '2020-01-05 22:35:04', '2020-01-06 13:14:50', 'voyager.media.index', NULL),
(3, 1, 'Users', '', '_self', 'voyager-person', NULL, NULL, 7, '2020-01-05 22:35:04', '2020-01-06 13:14:50', 'voyager.users.index', NULL),
(4, 1, 'Roles', '', '_self', 'voyager-lock', NULL, 3, 1, '2020-01-05 22:35:04', '2020-01-05 22:45:55', 'voyager.roles.index', NULL),
(5, 1, 'Tools', '', '_self', 'voyager-tools', NULL, NULL, 9, '2020-01-05 22:35:04', '2020-01-06 13:14:50', NULL, NULL),
(6, 1, 'Menu Builder', '', '_self', 'voyager-list', NULL, 5, 1, '2020-01-05 22:35:04', '2020-01-06 07:16:56', 'voyager.menus.index', NULL),
(7, 1, 'Database', '', '_self', 'voyager-data', NULL, 5, 2, '2020-01-05 22:35:04', '2020-01-06 07:16:56', 'voyager.database.index', NULL),
(8, 1, 'Compass', '', '_self', 'voyager-compass', NULL, 5, 3, '2020-01-05 22:35:04', '2020-01-06 07:16:56', 'voyager.compass.index', NULL),
(9, 1, 'BREAD', '', '_self', 'voyager-bread', NULL, 5, 4, '2020-01-05 22:35:04', '2020-01-06 07:16:56', 'voyager.bread.index', NULL),
(10, 1, 'Settings', '', '_self', 'voyager-settings', NULL, NULL, 10, '2020-01-05 22:35:04', '2020-01-06 13:14:50', 'voyager.settings.index', NULL),
(11, 1, 'Hooks', '', '_self', 'voyager-hook', NULL, 5, 5, '2020-01-05 22:35:04', '2020-01-06 07:16:56', 'voyager.hooks', NULL),
(15, 1, 'Контрагенты', '', '_self', 'voyager-list', '#000000', NULL, 1, '2020-01-05 23:05:11', '2020-01-06 13:05:22', 'voyager.contragents.index', 'null'),
(16, 1, 'Федеральные округа', '', '_self', 'voyager-list', '#000000', NULL, 3, '2020-01-06 07:07:53', '2020-01-06 13:14:53', 'voyager.federal-districts.index', 'null'),
(18, 1, 'Области', '', '_self', 'voyager-list', '#000000', NULL, 4, '2020-01-06 07:11:06', '2020-01-06 13:14:53', 'voyager.regions.index', 'null'),
(19, 1, 'Тип предприятия', '', '_self', 'voyager-list', '#000000', NULL, 5, '2020-01-06 10:48:22', '2020-01-06 13:14:53', 'voyager.types.index', 'null'),
(20, 1, 'Склады', '', '_self', 'voyager-list', '#000000', NULL, 2, '2020-01-06 13:08:37', '2020-01-06 13:15:17', 'voyager.stores.index', 'null');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_01_01_000000_add_voyager_user_fields', 1),
(4, '2016_01_01_000000_create_data_types_table', 1),
(5, '2016_05_19_173453_create_menu_table', 1),
(6, '2016_10_21_190000_create_roles_table', 1),
(7, '2016_10_21_190000_create_settings_table', 1),
(8, '2016_11_30_135954_create_permission_table', 1),
(9, '2016_11_30_141208_create_permission_role_table', 1),
(10, '2016_12_26_201236_data_types__add__server_side', 1),
(11, '2017_01_13_000000_add_route_to_menu_items_table', 1),
(12, '2017_01_14_005015_create_translations_table', 1),
(13, '2017_01_15_000000_make_table_name_nullable_in_permissions_table', 1),
(14, '2017_03_06_000000_add_controller_to_data_types_table', 1),
(15, '2017_04_21_000000_add_order_to_data_rows_table', 1),
(16, '2017_07_05_210000_add_policyname_to_data_types_table', 1),
(17, '2017_08_05_000000_add_group_to_settings_table', 1),
(18, '2017_11_26_013050_add_user_role_relationship', 1),
(19, '2017_11_26_015000_create_user_roles_table', 1),
(20, '2018_03_11_000000_add_user_settings', 1),
(21, '2018_03_14_000000_add_details_to_data_types_table', 1),
(22, '2018_03_16_000000_make_settings_value_nullable', 1),
(23, '2019_08_19_000000_create_failed_jobs_table', 1),
(24, '2016_01_01_000000_create_pages_table', 2),
(25, '2016_01_01_000000_create_posts_table', 2),
(26, '2016_02_15_204651_create_categories_table', 2),
(27, '2017_04_11_000000_alter_post_nullable_fields_table', 2),
(28, '2020_01_05_225714_create_contragents_table', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `permissions`
--

INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES
(1, 'browse_admin', NULL, '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(2, 'browse_bread', NULL, '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(3, 'browse_database', NULL, '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(4, 'browse_media', NULL, '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(5, 'browse_compass', NULL, '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(6, 'browse_menus', 'menus', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(7, 'read_menus', 'menus', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(8, 'edit_menus', 'menus', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(9, 'add_menus', 'menus', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(10, 'delete_menus', 'menus', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(11, 'browse_roles', 'roles', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(12, 'read_roles', 'roles', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(13, 'edit_roles', 'roles', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(14, 'add_roles', 'roles', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(15, 'delete_roles', 'roles', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(16, 'browse_users', 'users', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(17, 'read_users', 'users', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(18, 'edit_users', 'users', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(19, 'add_users', 'users', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(20, 'delete_users', 'users', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(21, 'browse_settings', 'settings', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(22, 'read_settings', 'settings', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(23, 'edit_settings', 'settings', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(24, 'add_settings', 'settings', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(25, 'delete_settings', 'settings', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(26, 'browse_hooks', NULL, '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(42, 'browse_contragents', 'contragents', '2020-01-05 23:05:11', '2020-01-05 23:05:11'),
(43, 'read_contragents', 'contragents', '2020-01-05 23:05:11', '2020-01-05 23:05:11'),
(44, 'edit_contragents', 'contragents', '2020-01-05 23:05:11', '2020-01-05 23:05:11'),
(45, 'add_contragents', 'contragents', '2020-01-05 23:05:11', '2020-01-05 23:05:11'),
(46, 'delete_contragents', 'contragents', '2020-01-05 23:05:11', '2020-01-05 23:05:11'),
(47, 'browse_federal_districts', 'federal_districts', '2020-01-06 07:07:53', '2020-01-06 07:07:53'),
(48, 'read_federal_districts', 'federal_districts', '2020-01-06 07:07:53', '2020-01-06 07:07:53'),
(49, 'edit_federal_districts', 'federal_districts', '2020-01-06 07:07:53', '2020-01-06 07:07:53'),
(50, 'add_federal_districts', 'federal_districts', '2020-01-06 07:07:53', '2020-01-06 07:07:53'),
(51, 'delete_federal_districts', 'federal_districts', '2020-01-06 07:07:53', '2020-01-06 07:07:53'),
(57, 'browse_regions', 'regions', '2020-01-06 07:11:06', '2020-01-06 07:11:06'),
(58, 'read_regions', 'regions', '2020-01-06 07:11:06', '2020-01-06 07:11:06'),
(59, 'edit_regions', 'regions', '2020-01-06 07:11:06', '2020-01-06 07:11:06'),
(60, 'add_regions', 'regions', '2020-01-06 07:11:06', '2020-01-06 07:11:06'),
(61, 'delete_regions', 'regions', '2020-01-06 07:11:06', '2020-01-06 07:11:06'),
(62, 'browse_types', 'types', '2020-01-06 10:48:22', '2020-01-06 10:48:22'),
(63, 'read_types', 'types', '2020-01-06 10:48:22', '2020-01-06 10:48:22'),
(64, 'edit_types', 'types', '2020-01-06 10:48:22', '2020-01-06 10:48:22'),
(65, 'add_types', 'types', '2020-01-06 10:48:22', '2020-01-06 10:48:22'),
(66, 'delete_types', 'types', '2020-01-06 10:48:22', '2020-01-06 10:48:22'),
(67, 'browse_stores', 'stores', '2020-01-06 13:08:37', '2020-01-06 13:08:37'),
(68, 'read_stores', 'stores', '2020-01-06 13:08:37', '2020-01-06 13:08:37'),
(69, 'edit_stores', 'stores', '2020-01-06 13:08:37', '2020-01-06 13:08:37'),
(70, 'add_stores', 'stores', '2020-01-06 13:08:37', '2020-01-06 13:08:37'),
(71, 'delete_stores', 'stores', '2020-01-06 13:08:37', '2020-01-06 13:08:37');

-- --------------------------------------------------------

--
-- Структура таблицы `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE `regions` (
  `id` int(10) UNSIGNED NOT NULL,
  `federal_district_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `regions`
--

INSERT INTO `regions` (`id`, `federal_district_id`, `title`, `created_at`, `updated_at`) VALUES
(1, 2, '31 Белгородская область', '2020-01-06 07:15:00', '2020-01-06 14:09:46'),
(2, 2, '62 Рязанская область', '2020-01-06 07:20:00', '2020-01-06 14:10:10'),
(3, 5, '01 Адыгея Республика', '2020-01-06 07:20:00', '2020-01-06 14:01:30'),
(4, 4, '02 Башкортостан Республика', '2020-01-06 07:40:00', '2020-01-06 14:01:42'),
(5, 6, '03 Бурятия Республика', '2020-01-06 07:40:00', '2020-01-06 14:01:58'),
(6, 6, '04 Республика Алтай', '2020-01-06 07:41:00', '2020-01-06 14:02:52'),
(7, 8, '05 Дагестан Республика', '2020-01-06 07:41:00', '2020-01-06 14:02:10'),
(8, 8, '06 Ингушетия Республика', '2020-01-06 07:42:00', '2020-01-06 14:03:12'),
(9, 8, '07 Кабардино-Балкарская Республика', '2020-01-06 07:42:00', '2020-01-06 14:03:02'),
(10, 5, '08 Республика Калмыкия', '2020-01-06 07:45:00', '2020-01-06 14:03:24'),
(11, 8, '09 Республика Карачаево-Черкесия', '2020-01-06 14:01:03', '2020-01-06 14:01:03'),
(12, 3, '10 Республика Карелия', '2020-01-06 14:03:43', '2020-01-06 14:03:43'),
(13, 3, '11 Республика Коми', '2020-01-06 14:03:58', '2020-01-06 14:03:58'),
(14, 4, '12 Республика Марий Эл', '2020-01-06 14:04:14', '2020-01-06 14:04:14'),
(15, 4, '13 Республика Мордовия', '2020-01-06 14:04:34', '2020-01-06 14:04:34'),
(16, 7, '14 Республика Саха (Якутия)', '2020-01-06 14:04:52', '2020-01-06 14:04:52'),
(17, 8, '15 Республика Северная Осетия — Алания', '2020-01-06 14:05:08', '2020-01-06 14:05:08'),
(18, 4, '16 Республика Татарстан', '2020-01-06 14:05:26', '2020-01-06 14:05:26'),
(19, 6, '17 Республика Тыва', '2020-01-06 14:05:44', '2020-01-06 14:05:44'),
(20, 4, '18 Удмуртская Республика', '2020-01-06 14:06:00', '2020-01-06 14:06:00'),
(21, 6, '19 Республика Хакасия', '2020-01-06 14:06:24', '2020-01-06 14:06:24'),
(22, 4, '21 Чувашская Республика', '2020-01-06 14:06:43', '2020-01-06 14:06:43'),
(23, 6, '22 Алтайский край', '2020-01-06 14:06:59', '2020-01-06 14:06:59'),
(24, 5, '23 Краснодарский край', '2020-01-06 14:07:18', '2020-01-06 14:07:18'),
(25, 6, '24 Красноярский край', '2020-01-06 14:07:39', '2020-01-06 14:07:39'),
(26, 8, '26 Ставропольский край', '2020-01-06 14:08:10', '2020-01-06 14:08:10'),
(27, 7, '27 Хабаровский край', '2020-01-06 14:08:26', '2020-01-06 14:08:26'),
(28, 7, '28 Амурская область', '2020-01-06 14:08:41', '2020-01-06 14:08:41'),
(29, 3, '29 Архангельская область', '2020-01-06 14:08:55', '2020-01-06 14:08:55'),
(30, 5, '30 Астраханская область', '2020-01-06 14:10:44', '2020-01-06 14:10:44'),
(31, 2, '32 Брянская область', '2020-01-06 14:11:04', '2020-01-06 14:11:04'),
(32, 2, '33 Владимирская область', '2020-01-06 14:11:27', '2020-01-06 14:11:27'),
(33, 5, '34 Волгоградская область', '2020-01-06 14:11:42', '2020-01-06 14:11:42'),
(34, 3, '35 Вологодская область', '2020-01-06 14:11:57', '2020-01-06 14:11:57'),
(35, 2, '36 Воронежская область', '2020-01-06 14:12:17', '2020-01-06 14:12:17'),
(36, 2, '37 Ивановская область', '2020-01-06 14:12:35', '2020-01-06 14:12:35'),
(37, 6, '38 Иркутская область', '2020-01-06 14:12:50', '2020-01-06 14:12:50'),
(38, 3, '39 Калининградская область', '2020-01-06 14:13:05', '2020-01-06 14:13:05'),
(39, 2, '40 Калужская область', '2020-01-06 14:13:24', '2020-01-06 14:13:24'),
(40, 7, '41 Камчатский край', '2020-01-06 14:13:48', '2020-01-06 14:13:48'),
(41, 6, '42 Кемеровская область', '2020-01-06 14:14:09', '2020-01-06 14:14:09'),
(42, 4, '43 Кировская область', '2020-01-06 14:14:24', '2020-01-06 14:14:24'),
(43, 2, '44 Костромская область', '2020-01-06 14:14:39', '2020-01-06 14:14:39'),
(44, 9, '45 Курганская область', '2020-01-06 14:15:27', '2020-01-06 14:15:27'),
(45, 2, '46 Курская область', '2020-01-06 14:15:41', '2020-01-06 14:15:41'),
(46, 3, '47 Ленинградская область', '2020-01-06 14:15:54', '2020-01-06 14:15:54'),
(47, 2, '48 Липецкая область', '2020-01-06 14:16:09', '2020-01-06 14:16:09'),
(48, 6, '49 Магаданская область', '2020-01-06 14:16:24', '2020-01-06 14:16:24'),
(49, 2, '50 Московская область', '2020-01-06 14:16:42', '2020-01-06 14:16:42'),
(50, 3, '51 Мурманская область', '2020-01-06 14:16:54', '2020-01-06 14:16:54'),
(51, 4, '52 Нижегородская область', '2020-01-06 14:17:14', '2020-01-06 14:17:14'),
(52, 3, '53 Новгородская область', '2020-01-06 14:17:27', '2020-01-06 14:17:27'),
(53, 6, '54 Новосибирская область', '2020-01-06 14:17:50', '2020-01-06 14:17:50'),
(54, 6, '55 Омская область', '2020-01-06 14:18:07', '2020-01-06 14:18:07'),
(55, 4, '56 Оренбургская область', '2020-01-06 14:18:22', '2020-01-06 14:18:22'),
(56, 2, '57 Орловская область', '2020-01-06 14:18:39', '2020-01-06 14:18:39'),
(57, 4, '58 Пензенская область', '2020-01-06 14:18:53', '2020-01-06 14:18:53'),
(58, 4, '59 Пермский край', '2020-01-06 14:19:10', '2020-01-06 14:19:10'),
(59, 3, '60 Псковская область', '2020-01-06 14:19:26', '2020-01-06 14:19:26'),
(60, 5, '61 Ростовская область', '2020-01-06 14:19:42', '2020-01-06 14:19:42'),
(61, 4, '63 Самарская область', '2020-01-06 14:20:02', '2020-01-06 14:20:02'),
(62, 4, '64 Саратовская область', '2020-01-06 14:21:45', '2020-01-06 14:21:45'),
(63, 7, '65 Сахалинская область', '2020-01-06 14:22:05', '2020-01-06 14:22:05'),
(64, 9, '66 Свердловская область', '2020-01-06 14:22:22', '2020-01-06 14:22:22'),
(65, 2, '67 Смоленская область', '2020-01-06 14:22:36', '2020-01-06 14:22:36'),
(66, 2, '68 Тамбовская область', '2020-01-06 14:22:48', '2020-01-06 14:22:48'),
(67, 2, '69 Тверская область', '2020-01-06 14:23:01', '2020-01-06 14:23:01'),
(68, 6, '70 Томская область', '2020-01-06 14:23:18', '2020-01-06 14:23:18'),
(69, 2, '71 Тульская область', '2020-01-06 14:23:34', '2020-01-06 14:23:34'),
(70, 9, '72 Тюменская область', '2020-01-06 14:23:50', '2020-01-06 14:23:50'),
(71, 4, '73 Ульяновская область', '2020-01-06 14:24:07', '2020-01-06 14:24:07'),
(72, 9, '74 Челябинская область', '2020-01-06 14:24:26', '2020-01-06 14:24:26'),
(73, 6, '75 Забайкальский край', '2020-01-06 14:24:41', '2020-01-06 14:24:41'),
(74, 2, '76 Ярославская область', '2020-01-06 14:24:54', '2020-01-06 14:24:54'),
(75, 2, '77 г. Москва', '2020-01-06 14:25:07', '2020-01-06 14:25:07'),
(76, 3, '78 г. Санкт-Петербург', '2020-01-06 14:25:21', '2020-01-06 14:25:21'),
(77, 7, '79 Еврейская автономная область', '2020-01-06 14:25:38', '2020-01-06 14:25:38'),
(78, 3, '83 Ненецкий автономный округ', '2020-01-06 14:25:53', '2020-01-06 14:25:53'),
(79, 9, '86 Ханты-Мансийский автономный округ - Югра', '2020-01-06 14:26:09', '2020-01-06 14:26:09'),
(80, 7, '87 Чукотский автономный округ', '2020-01-06 14:26:25', '2020-01-06 14:26:25'),
(81, 9, '89 Ямало-Ненецкий автономный округ', '2020-01-06 14:26:39', '2020-01-06 14:26:39'),
(82, 8, '95 Чеченская республика', '2020-01-06 14:26:57', '2020-01-06 14:26:57');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', '2020-01-05 22:35:04', '2020-01-05 22:35:04'),
(2, 'user', 'Normal User', '2020-01-05 22:35:04', '2020-01-05 22:35:04');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `details` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES
(1, 'site.title', 'Site Title', 'Site Title', '', 'text', 1, 'Site'),
(2, 'site.description', 'Site Description', 'Site Description', '', 'text', 2, 'Site'),
(3, 'site.logo', 'Site Logo', '', '', 'image', 3, 'Site'),
(4, 'site.google_analytics_tracking_id', 'Google Analytics Tracking ID', '', '', 'text', 4, 'Site'),
(5, 'admin.bg_image', 'Admin Background Image', '', '', 'image', 5, 'Admin'),
(6, 'admin.title', 'Admin Title', 'Voyager', '', 'text', 1, 'Admin'),
(7, 'admin.description', 'Admin Description', 'Welcome to Voyager. The Missing Admin for Laravel', '', 'text', 2, 'Admin'),
(8, 'admin.loader', 'Admin Loader', '', '', 'image', 3, 'Admin'),
(9, 'admin.icon_image', 'Admin Icon Image', '', '', 'image', 4, 'Admin'),
(10, 'admin.google_analytics_client_id', 'Google Analytics Client ID (used for admin dashboard)', '', '', 'text', 1, 'Admin');

-- --------------------------------------------------------

--
-- Структура таблицы `stores`
--

CREATE TABLE `stores` (
  `id` int(10) UNSIGNED NOT NULL,
  `coords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `contragent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `stores`
--

INSERT INTO `stores` (`id`, `coords`, `address`, `created_at`, `updated_at`, `contragent_id`) VALUES
(1, '50.3,72.6', 'Москва', '2020-01-06 13:09:00', '2020-01-06 13:13:20', 1),
(2, '50.3,72.6', 'Москва', '2020-01-06 13:10:00', '2020-01-06 13:14:27', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `translations`
--

CREATE TABLE `translations` (
  `id` int(10) UNSIGNED NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int(10) UNSIGNED NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `translations`
--

INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES
(1, 'data_types', 'display_name_singular', 5, 'pt', 'Post', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(2, 'data_types', 'display_name_singular', 6, 'pt', 'Página', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(3, 'data_types', 'display_name_singular', 1, 'pt', 'Utilizador', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(4, 'data_types', 'display_name_singular', 4, 'pt', 'Categoria', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(5, 'data_types', 'display_name_singular', 2, 'pt', 'Menu', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(6, 'data_types', 'display_name_singular', 3, 'pt', 'Função', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(7, 'data_types', 'display_name_plural', 5, 'pt', 'Posts', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(8, 'data_types', 'display_name_plural', 6, 'pt', 'Páginas', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(9, 'data_types', 'display_name_plural', 1, 'pt', 'Utilizadores', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(10, 'data_types', 'display_name_plural', 4, 'pt', 'Categorias', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(11, 'data_types', 'display_name_plural', 2, 'pt', 'Menus', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(12, 'data_types', 'display_name_plural', 3, 'pt', 'Funções', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(13, 'categories', 'slug', 1, 'pt', 'categoria-1', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(14, 'categories', 'name', 1, 'pt', 'Categoria 1', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(15, 'categories', 'slug', 2, 'pt', 'categoria-2', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(16, 'categories', 'name', 2, 'pt', 'Categoria 2', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(17, 'pages', 'title', 1, 'pt', 'Olá Mundo', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(18, 'pages', 'slug', 1, 'pt', 'ola-mundo', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(19, 'pages', 'body', 1, 'pt', '<p>Olá Mundo. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\r\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(20, 'menu_items', 'title', 1, 'pt', 'Painel de Controle', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(21, 'menu_items', 'title', 2, 'pt', 'Media', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(22, 'menu_items', 'title', 13, 'pt', 'Publicações', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(23, 'menu_items', 'title', 3, 'pt', 'Utilizadores', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(24, 'menu_items', 'title', 12, 'pt', 'Categorias', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(25, 'menu_items', 'title', 14, 'pt', 'Páginas', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(26, 'menu_items', 'title', 4, 'pt', 'Funções', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(27, 'menu_items', 'title', 5, 'pt', 'Ferramentas', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(28, 'menu_items', 'title', 6, 'pt', 'Menus', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(29, 'menu_items', 'title', 7, 'pt', 'Base de dados', '2020-01-05 22:35:21', '2020-01-05 22:35:21'),
(30, 'menu_items', 'title', 10, 'pt', 'Configurações', '2020-01-05 22:35:21', '2020-01-05 22:35:21');

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--

CREATE TABLE `types` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `types`
--

INSERT INTO `types` (`id`, `title`, `created_at`, `updated_at`) VALUES
(2, 'Мясокомбинат', '2020-01-06 12:58:25', '2020-01-06 12:58:25'),
(3, 'Свинокомплекс', '2020-01-06 12:58:36', '2020-01-06 12:58:36');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `avatar`, `email_verified_at`, `password`, `remember_token`, `settings`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'pimax1978@icloud.com', 'users/default.png', NULL, '$2y$10$k7YtrUIoAVjS3rDcpwykVudg.s8Jw6kh.dJnRJg7.XzdPe7oLAp.e', 'EFu0y6FBXikSuJzmpPFQS5yklAVbBzO1CzWURb9IVFOeM6LYPgBMWoLLBCGH', NULL, '2020-01-05 22:32:11', '2020-01-05 22:37:33');

-- --------------------------------------------------------

--
-- Структура таблицы `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `contragents`
--
ALTER TABLE `contragents`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `contragent_type`
--
ALTER TABLE `contragent_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contragent_type_contragent_id_index` (`contragent_id`),
  ADD KEY `contragent_type_type_id_index` (`type_id`);

--
-- Индексы таблицы `data_rows`
--
ALTER TABLE `data_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_rows_data_type_id_foreign` (`data_type_id`);

--
-- Индексы таблицы `data_types`
--
ALTER TABLE `data_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data_types_name_unique` (`name`),
  ADD UNIQUE KEY `data_types_slug_unique` (`slug`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `federal_districts`
--
ALTER TABLE `federal_districts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_name_unique` (`name`);

--
-- Индексы таблицы `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_id_foreign` (`menu_id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_key_index` (`key`);

--
-- Индексы таблицы `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Индексы таблицы `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`);

--
-- Индексы таблицы `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Индексы таблицы `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `user_roles_user_id_index` (`user_id`),
  ADD KEY `user_roles_role_id_index` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `contragents`
--
ALTER TABLE `contragents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `contragent_type`
--
ALTER TABLE `contragent_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `data_rows`
--
ALTER TABLE `data_rows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT для таблицы `data_types`
--
ALTER TABLE `data_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `federal_districts`
--
ALTER TABLE `federal_districts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT для таблицы `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT для таблицы `types`
--
ALTER TABLE `types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `data_rows`
--
ALTER TABLE `data_rows`
  ADD CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
