-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.0
-- Время создания: Июн 06 2025 г., 09:32
-- Версия сервера: 8.0.35
-- Версия PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `forward`
--

-- --------------------------------------------------------

--
-- Структура таблицы `body_types`
--

CREATE TABLE `body_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `body_types`
--

INSERT INTO `body_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Седан', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(2, 'Хэтчбек', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(3, 'Универсал', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(4, 'Купе', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(5, 'Кабриолет', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(6, 'Внедорожник', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(7, 'Кроссовер', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(8, 'Минивэн', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(9, 'Пикап', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(10, 'Фургон', '2025-05-30 20:39:01', '2025-05-30 20:39:01');

-- --------------------------------------------------------

--
-- Структура таблицы `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `car_id` bigint UNSIGNED NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `appointment_date` timestamp NULL DEFAULT NULL,
  `status` enum('pending','confirmed','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `manager_comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `manager_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `branches`
--

CREATE TABLE `branches` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Форвард Авто Интерлайн', 'Иркутск, Советская улица, 73', 'branches/yIxR3sGEAMiu561Jk00RWqXYnsLaHWSRvrRcCKj9.jpg', '2025-05-30 20:42:45', '2025-05-30 20:42:45'),
(2, 'Форвард Авто в Новоленино', 'Иркутск, улица Ярославского, 302', 'branches/8gGqF8UMOoGF8Hg5WW5Q1xC6Lub0lsXsC5mFsvsv.jpg', '2025-05-30 20:43:12', '2025-05-30 20:43:12'),
(3, 'Форвард Авто на Ленина', 'Иркутск, улица Ленина, 5А', 'branches/ea7U5gKoFj8g8X8J9YvQiffiRJlDRDK9NcqkgqzP.jpg', '2025-05-30 20:44:19', '2025-05-30 20:44:19'),
(4, 'Форвард Авто на Трактовой', 'Иркутск, Трактовая улица, 22А', 'branches/qDZAvZe1uziQ7A5ZlAVVEjN9vRUwn7oO7n4ka8Ox.jpg', '2025-05-30 20:45:02', '2025-05-30 20:45:02');

-- --------------------------------------------------------

--
-- Структура таблицы `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'BMW', 'brands/mAjrQ2QXMQOwX3grhlHybzKS2YjCEXxwcIKtdUFq.png', '2025-05-30 20:45:20', '2025-05-30 20:52:31'),
(2, 'Nissan', 'brands/Gso434PhPfv5XIFpolOKR0n2ZY9fgJipfqRtm87T.png', '2025-05-30 20:45:30', '2025-05-30 20:45:30'),
(3, 'Toyota', 'brands/DHY02xDiiYpZReSC4Mk9p5Kn7cTMnXk7l6jJjc2G.png', '2025-05-30 20:45:40', '2025-05-30 20:45:40'),
(4, 'Mercedes Benz', 'brands/vRmuLltCzjwHQd3173AO0HYNcAvIHXXn4Ih63859.png', '2025-05-30 20:45:54', '2025-05-30 20:45:54'),
(5, 'Cadillac', 'brands/ROGZNo55sNSPYmgLLf8p4JK6AfCWnbhcigwj5C8a.png', '2025-05-30 20:46:10', '2025-05-30 20:46:10'),
(6, 'Porsche', 'brands/XAhrVEUv8Vum8sx5vED3tkIUq06D8HyD7aJrMMSO.png', '2025-05-30 20:46:18', '2025-05-30 20:46:18'),
(7, 'Ford', 'brands/JolNpXQHHJujrzHEccg4d4kXvDjbqAfsWx2SB28H.png', '2025-05-30 20:46:38', '2025-05-30 20:46:38'),
(8, 'Лада', 'brands/3yoe66jEztefPeywx4752uipiuhCSH3M3sEG3URH.png', '2025-05-30 20:47:46', '2025-05-30 20:47:46'),
(9, 'Skoda', 'brands/7vje5L9IaBQoyRSzDklJWcxy2Gh0exjbK1Fuan6q.png', '2025-05-30 20:48:50', '2025-05-30 20:48:50'),
(10, 'Mitsubishi', 'brands/F8UO0i91jFOkaJDpaCkkPD2GqDkrwbAoDJz6Ehaf.png', '2025-05-30 20:50:15', '2025-05-30 20:50:15');

-- --------------------------------------------------------

--
-- Структура таблицы `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cars`
--

CREATE TABLE `cars` (
  `id` bigint UNSIGNED NOT NULL,
  `equipment_id` bigint UNSIGNED NOT NULL,
  `vin` varchar(17) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mileage` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_sold` tinyint(1) NOT NULL DEFAULT '0',
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `color_id` bigint UNSIGNED DEFAULT NULL,
  `custom_color_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_color_hex` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cars`
--

INSERT INTO `cars` (`id`, `equipment_id`, `vin`, `mileage`, `price`, `description`, `is_sold`, `branch_id`, `color_id`, `custom_color_name`, `custom_color_hex`, `created_at`, `updated_at`) VALUES
(1, 1, 'JHMCM56557C404453', 12000, 10000000.00, 'Продаётся BMW M5 — легендарный представитель спортивного премиум-класса, сочетающий в себе мощь, комфорт и безупречный стиль. Это автомобиль для тех, кто ценит динамику, управляемость и роскошь в каждом элементе. Под капотом — турбированный V8, развивающий потрясающую мощность, разгоняющий машину с нуля до сотни за считанные секунды. В салоне — кожаная отделка, современный мультимедийный комплекс, спортивные сиденья и всё необходимое для комфортных поездок. Машина в отличном состоянии, полностью обслужена, с документами в порядке. Идеальный вариант для истинного ценителя скорости и качества. Белый металлик, атмосфера премиума, уверенное поведение на дороге — всё это BMW M5.', 0, 1, 1, NULL, NULL, '2025-05-30 22:55:01', '2025-05-30 22:55:01'),
(2, 2, 'WVGZZZCAZJC980456', 12000, 5000000.00, 'Продаётся BMW X7 — флагманский кроссовер премиум-класса, сочетающий в себе роскошь, технологии и внушительный внешний вид. Это автомобиль для тех, кто ценит простор, комфорт и статус. Мощный двигатель обеспечивает уверенный разгон и уверенную динамику даже на скоростных трассах, а полный привод и адаптивная подвеска делают поездки максимально комфортными. Салон отделан высококачественными материалами, сиденья с подогревом и вентиляцией, третий ряд позволяет брать в дорогу всю семью. Огромный багажник, продвинутая мультимедийная система, камеры, климат-контроль на всех рядах — всё здесь создано для максимального удобства. Машина в идеальном состоянии, без пробега по РФ, обслужена у официального дилера. Идеальный вариант для семьи или деловых поездок. Роскошь, мощь, статус — BMW X7 ждёт своего владельца.', 0, 2, 4, NULL, NULL, '2025-05-30 22:56:44', '2025-05-30 22:56:44'),
(3, 5, 'WVGZZZCAZJC676758', 50000, 2000000.00, 'Продаётся Toyota Mark II — культовый японский седан, сочетающий в себе элегантность, надёжность и спортивный характер. Этот автомобиль остаётся фаворитом среди ценителей классического дизайна и отличной управляемости. Внешне — строгий силуэт, хромированные детали и бодрый спортивный нос. Под капотом — проверенный временем рядный шестицилиндровый мотор, сочетающий баланс между динамикой и экономичностью. Салон оформлен в духе японской лаконичности: удобные сиденья, минимум лишних деталей и максимум комфорта. Машина в хорошем состоянии, на ходу всё работает без замечаний. Идеальный вариант для коллекционера, ценителя классики или тех, кто ищет надёжный и стильный автомобиль с характером. Toyota Mark II — не просто машина, а часть истории автоспорта.', 0, 2, 8, NULL, NULL, '2025-05-30 22:59:54', '2025-05-30 23:00:17');

-- --------------------------------------------------------

--
-- Структура таблицы `car_images`
--

CREATE TABLE `car_images` (
  `id` bigint UNSIGNED NOT NULL,
  `car_id` bigint UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `car_images`
--

INSERT INTO `car_images` (`id`, `car_id`, `path`, `is_main`, `created_at`, `updated_at`) VALUES
(1, 1, 'cars/tVve2KgGrx8kRn2rTO8WJhyUIIGeH8Bjdj6XQiNh.jpg', 0, '2025-05-30 22:55:01', '2025-05-30 22:55:16'),
(2, 1, 'cars/jPo0f9Ox0cycwyGEWp0J2D9OJy69RTjOdVuMBPkB.jpg', 0, '2025-05-30 22:55:01', '2025-05-30 22:55:16'),
(3, 1, 'cars/Sabp4fjCij6PlfmRrSPQO1tBqi7n8Gte4s8u59GM.jpg', 0, '2025-05-30 22:55:01', '2025-05-30 22:55:16'),
(4, 1, 'cars/LAP9cSaehBqSBoSwFrdar91f76iPa3FvVBlPKCPf.jpg', 1, '2025-05-30 22:55:02', '2025-05-30 22:55:16'),
(5, 1, 'cars/dKijiInbcu6pCiOCaExxT06R6tvNDs9izlPS9lAP.jpg', 0, '2025-05-30 22:55:02', '2025-05-30 22:55:16'),
(6, 1, 'cars/97629OTgZhIWT7PWEjVJobWr4v1DO2LDimIeH5Zb.jpg', 0, '2025-05-30 22:55:02', '2025-05-30 22:55:16'),
(7, 1, 'cars/Zt2r6eWJue80wcQIXU4yek4EwOrFOHDt0Q9goDuz.jpg', 0, '2025-05-30 22:55:02', '2025-05-30 22:55:16'),
(8, 1, 'cars/NqK6rswcwXL27kX46h5g0V5uc6IeUlC0RsyZx8o5.jpg', 0, '2025-05-30 22:55:02', '2025-05-30 22:55:16'),
(9, 2, 'cars/Pa22QUzAULFeR9j0HfW5TMzXH3j6EHhm8j6F02lP.jpg', 0, '2025-05-30 22:56:44', '2025-05-30 23:00:35'),
(10, 2, 'cars/vs0Kz6ruSWhr9UiWjVsjC8rtEkTGmKvrzKUErG44.jpg', 0, '2025-05-30 22:56:44', '2025-05-30 23:00:35'),
(11, 2, 'cars/LTRKJSuF8yorK6Mdhp39fssNwdjfr2EDSOymZQlI.jpg', 0, '2025-05-30 22:56:44', '2025-05-30 23:00:35'),
(12, 2, 'cars/z2zf1ZhWxYyHqsIiMjFyQHHcG7xhlzYgtO9f3vbP.jpg', 0, '2025-05-30 22:56:44', '2025-05-30 23:00:35'),
(13, 2, 'cars/ddRb6yeRi1mPpsehTYFjBEzEehCfqa5bxIkSwNli.jpg', 0, '2025-05-30 22:56:44', '2025-05-30 23:00:35'),
(14, 2, 'cars/CCQpp4vNInCaAyE86KNYmNH5Hxld0V8QqxtTaSdg.jpg', 1, '2025-05-30 22:56:44', '2025-05-30 23:00:35'),
(15, 2, 'cars/YVaEBgAPe6l856jD0HiImSzGyynqXTb4rWLvfGJb.jpg', 0, '2025-05-30 22:56:45', '2025-05-30 23:00:35'),
(16, 2, 'cars/O9BFZFuKHhg09ZQ0ILKqbSvjQlwXR0yTQR1Dtenx.jpg', 0, '2025-05-30 22:56:45', '2025-05-30 23:00:35'),
(17, 2, 'cars/WcJreofp2vjRNcFO3EjHuvJtRQ4UChblE7eaH9AL.jpg', 0, '2025-05-30 22:56:45', '2025-05-30 23:00:35'),
(18, 3, 'cars/EHgCIO6ob2Z6pHrhKrnJ2fQo32L3P8YPh3WLJ005.jpg', 0, '2025-05-30 22:59:54', '2025-05-30 23:00:44'),
(19, 3, 'cars/z4xIDeHo4eL4pW0XEFMrzL2x7CsdfR8fpthhw67A.jpg', 0, '2025-05-30 22:59:54', '2025-05-30 23:00:44'),
(20, 3, 'cars/RmjAg73J68JPUnYCvArCH5UNlJWJYOpV87YPZgXH.jpg', 1, '2025-05-30 22:59:54', '2025-05-30 23:00:44'),
(21, 3, 'cars/ERDCBtKNBevY6Fv64A9P9qsYh3VkuNi6fS2gIFf1.jpg', 0, '2025-05-30 22:59:54', '2025-05-30 23:00:44'),
(22, 3, 'cars/D8ho92jkoXKExj2nvQnw52iC0yu8v9m2A0oA755G.jpg', 0, '2025-05-30 22:59:54', '2025-05-30 23:00:44'),
(23, 3, 'cars/jkyaMIvNuTJ7MvIQoy2wUKkync6DRuoN3qgsr8g2.jpg', 0, '2025-05-30 22:59:54', '2025-05-30 23:00:44'),
(24, 3, 'cars/G5BgmMJk9Cka41Ja6csmMAnAdSmRHvjVsTrWsjLI.jpg', 0, '2025-05-30 22:59:54', '2025-05-30 23:00:44'),
(25, 3, 'cars/KniLBfSnfdiC7T2zQRu7YJKRhhdu1vD2fsOlXBcJ.jpg', 0, '2025-05-30 22:59:54', '2025-05-30 23:00:44'),
(26, 3, 'cars/X7K8kH7OmLBE84J9NhFkvSUieTxdVpqKMjSnD9kn.jpg', 0, '2025-05-30 22:59:54', '2025-05-30 23:00:44'),
(27, 3, 'cars/cEbgZTvee3eKoKnKhngHvdOJ4SfwYnMvtDSC56JZ.jpg', 0, '2025-05-30 22:59:54', '2025-05-30 23:00:44');

-- --------------------------------------------------------

--
-- Структура таблицы `car_models`
--

CREATE TABLE `car_models` (
  `id` bigint UNSIGNED NOT NULL,
  `brand_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `car_models`
--

INSERT INTO `car_models` (`id`, `brand_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'M5', '2025-05-30 20:50:27', '2025-05-30 20:53:41'),
(2, 1, 'X7', '2025-05-30 20:50:36', '2025-05-30 20:50:36'),
(3, 2, 'GTR', '2025-05-30 20:53:52', '2025-05-30 20:53:52'),
(4, 2, 'X-Trail', '2025-05-30 20:54:00', '2025-05-30 20:54:00'),
(5, 3, 'Mark 2', '2025-05-30 20:54:11', '2025-05-30 20:54:11'),
(6, 3, 'Crown', '2025-05-30 20:54:23', '2025-05-30 20:54:23'),
(7, 4, 'C class', '2025-05-30 20:54:41', '2025-05-30 20:54:41'),
(8, 4, 'S class', '2025-05-30 20:54:56', '2025-05-30 20:54:56'),
(9, 5, 'CTS', '2025-05-30 20:55:12', '2025-05-30 20:55:12'),
(10, 5, 'Escalade', '2025-05-30 20:55:53', '2025-05-30 20:55:53'),
(11, 6, 'Panamera', '2025-05-30 20:56:05', '2025-05-30 20:56:05'),
(12, 6, '911', '2025-05-30 20:56:12', '2025-05-30 20:56:12'),
(13, 7, 'Raptor', '2025-05-30 20:56:24', '2025-05-30 20:56:24'),
(14, 7, 'Focus', '2025-05-30 20:57:02', '2025-05-30 20:57:02'),
(15, 8, 'Веста', '2025-05-30 20:57:13', '2025-05-30 20:57:13'),
(16, 8, 'Ларгус', '2025-05-30 20:57:29', '2025-05-30 20:57:29'),
(17, 9, 'Oktavia', '2025-05-30 20:57:39', '2025-05-30 20:57:39'),
(18, 9, 'Karoq', '2025-05-30 20:57:48', '2025-05-30 20:57:48'),
(19, 10, 'ASX', '2025-05-30 20:57:55', '2025-05-30 20:57:55'),
(20, 10, 'Lancer', '2025-05-30 20:58:03', '2025-05-30 20:58:03');

-- --------------------------------------------------------

--
-- Структура таблицы `colors`
--

CREATE TABLE `colors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex_code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `colors`
--

INSERT INTO `colors` (`id`, `name`, `hex_code`, `created_at`, `updated_at`) VALUES
(1, 'Красный авантюрин', '#cc5577', '2025-05-30 21:08:03', '2025-05-30 21:08:03'),
(2, 'Синий снэппер рокс', '#003251', '2025-05-30 21:09:46', '2025-05-30 21:10:17'),
(3, 'Темно-синяя жемчужина', '#1d2732', '2025-05-30 21:12:09', '2025-05-30 21:12:09'),
(4, 'Серебристый', '#c0c0c0', '2025-05-30 21:14:35', '2025-05-30 21:14:35'),
(5, 'Белый', '#FFFFFF', '2025-05-30 21:16:14', '2025-05-30 21:16:14'),
(6, 'Черный', '#000000', '2025-05-30 22:48:59', '2025-05-30 22:48:59'),
(7, 'Miami Blue', '#00A3E0', '2025-05-30 22:51:58', '2025-05-30 22:51:58'),
(8, 'Серый', '#808080', '2025-05-30 23:00:17', '2025-05-30 23:00:17');

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Россия', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(2, 'Германия', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(3, 'Япония', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(4, 'США', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(5, 'Китай', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(6, 'Корея', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(7, 'Франция', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(8, 'Италия', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(9, 'Великобритания', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(10, 'Швеция', '2025-05-30 20:39:01', '2025-05-30 20:39:01');

-- --------------------------------------------------------

--
-- Структура таблицы `drive_types`
--

CREATE TABLE `drive_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `drive_types`
--

INSERT INTO `drive_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Передний', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(2, 'Задний', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(3, 'Полный', '2025-05-30 20:39:01', '2025-05-30 20:39:01');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_types`
--

CREATE TABLE `engine_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `engine_types`
--

INSERT INTO `engine_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Бензиновый', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(2, 'Дизельный', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(3, 'Электрический', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(4, 'Гибридный', '2025-05-30 20:39:01', '2025-05-30 20:39:01');

-- --------------------------------------------------------

--
-- Структура таблицы `equipment`
--

CREATE TABLE `equipment` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generation_id` bigint UNSIGNED NOT NULL,
  `body_type_id` bigint UNSIGNED NOT NULL,
  `engine_type_id` bigint UNSIGNED NOT NULL,
  `transmission_type_id` bigint UNSIGNED NOT NULL,
  `drive_type_id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED NOT NULL,
  `engine_volume` decimal(3,1) NOT NULL,
  `engine_power` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `range` int NOT NULL,
  `max_speed` int NOT NULL,
  `model_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `equipment`
--

INSERT INTO `equipment` (`id`, `name`, `generation_id`, `body_type_id`, `engine_type_id`, `transmission_type_id`, `drive_type_id`, `country_id`, `engine_volume`, `engine_power`, `description`, `range`, `max_speed`, `model_path`, `created_at`, `updated_at`) VALUES
(1, 'M Special', 1, 1, 1, 1, 3, 2, 4.4, 625, 'Стильный и динамичный M5 F90 LCI — это воплощение спортивного премиум-класса от BMW. Обновлённый дизайн экстерьера с агрессивной передней частью, улучшенные аэродинамические свойства и интеллектуальные технологии делают этот автомобиль по-настоящему совершенным. Мощный 4,4-литровый V8 с двойным турбонаддувом выдаёт 625 л.с., обеспечивая впечатляющий разгон и уверенное прохождение поворотов. Салон выполнен из высококачественных материалов с вниманием к каждой детали — идеальное сочетание комфорта, технологичности и спортивного духа. M5 F90 LCI — не просто машина, а настоящий объект желания для ценителей скорости и стиля.', 500, 250, NULL, '2025-05-30 21:08:03', '2025-05-30 21:08:03'),
(2, 'M60I xDrive', 2, 6, 1, 3, 3, 2, 4.0, 520, 'BMW X7 G07 — флагманский кроссовер, сочетающий роскошь, технологии и внушительные габариты в идеальном союзе. С первого взгляда покоряет массивная решётка радиатора, солидный профиль и изысканная оптика с лазерными прожекторами. В интерьере — простор, утончённость отделки, индивидуальные места для пассажиров и передовые системы комфорта и безопасности. Под капотом — мощный 4,4-литровый V8 или 3-литровый рядный шестицилиндровый двигатель, обеспечивающие уверенный ход в любом темпе. BMW X7 G07 — это вершина премиального SUV: статусно, технологично, безупречно.', 550, 220, NULL, '2025-05-30 21:09:46', '2025-05-30 21:09:46'),
(3, 'Nismo', 3, 4, 1, 2, 3, 3, 3.8, 570, 'Nissan GT-R — легендарный спорткар, сочетающий экстремальную динамику, передовые технологии и безупречную управляемость. Стильный, агрессивный дизайн подчёркивает его боевой характер, а мощный 3,8-литровый V6 с двойным турбонаддувом развивает до 570 л.с., обеспечивая невероятное ускорение и стабильность на трассе. Полный привод, спортивная подвеска и продвинутая аэродинамика делают GT-R по-настоящему всесторонним чемпионом. В салоне — комфорт высокого уровня, качественные материалы и современная электроника. Nissan GT-R — это не просто машина, а настоящий гипер-спортивный флагман японского автопрома.', 400, 280, NULL, '2025-05-30 21:12:09', '2025-05-30 21:12:09'),
(4, 'Lux', 19, 6, 1, 4, 3, 3, 2.0, 180, 'Nissan X-Trail — идеальный выбор для тех, кто ценит комфорт, надежность и стиль в повседневных поездках. Этот компактный кроссовер сочетает в себе современный дизайн с выразительной передней панелью, просторным и функциональным салоном, а также адаптивным полным приводом и продвинутыми системами безопасности. Под капотом — экономичный и бодрый турбомотор, обеспечивающий уверенную динамику как в городе, так и на трассе. Nissan X-Trail — это автомобиль для семьи, путешествий и активного образа жизни, где каждый километр становится удовольствием.', 700, 180, NULL, '2025-05-30 21:14:35', '2025-05-30 21:14:35'),
(5, 'Tourer V', 4, 1, 1, 2, 2, 3, 3.0, 280, 'Toyota Mark II — культовый японский седан, известный своей надежностью, элегантным дизайном и отличной управляемостью. Стильный экстерьер с выразительными линиями, продуманный до мелочей интерьер и хорошо отлаженный двигатель делают его любимцем автолюбителей по всему миру. Этот автомобиль сочетает в себе комфорт городского кruйза и уверенную динамику на трассе. Toyota Mark II — не просто машина, а символ времени, когда стиль и качество шли рука об руку.', 400, 220, NULL, '2025-05-30 21:16:14', '2025-05-30 21:16:14'),
(6, 'Majesta', 5, 1, 1, 2, 3, 3, 2.2, 180, 'Toyota Crown — легендарный флагманский седан, олицетворяющий элегантность, комфорт и японское качество. С момента своего дебюта он остаётся символом премиального статуса и технологичности. Современная версия поражает стильным, динамичным дизайном, роскошным интерьером с богатой отделкой и продвинутыми системами безопасности и комфорта. Гибридная силовая установка сочетает мощность и экологичность, обеспечивая плавный ход и низкий расход топлива. Toyota Crown — это не просто автомобиль, а выражение уважения к водителю и пассажирам, где каждый момент за рулём становится удовольствием.', 600, 220, NULL, '2025-05-30 21:17:30', '2025-05-30 21:17:30'),
(7, 'C 200 4MATIC', 6, 1, 1, 3, 3, 2, 2.0, 250, 'Mercedes-Benz C-Class — воплощение элегантности, технологий и динамики в мире премиальных седанов. Стильный экстерьер с фирменной решеткой радиатора, изысканными фарами и гармоничными линиями подчёркивает статус владельца. В салоне царит атмосфера роскоши: мягкие материалы, интеллектуальные мультимедийные системы, продвинутые опции комфорта и безопасности. Благодаря разнообразию двигателей — от экономичных бензиновых до тяговитых дизелей — C-Class одинаково уверен как в городском потоке, так и на загородной трассе. Это автомобиль для тех, кто ценит стиль, качество и удовольствие от вождения каждый день.', 400, 220, NULL, '2025-05-30 22:06:07', '2025-05-30 22:06:07'),
(8, 'S450 4MATIC Luxury', 7, 1, 1, 3, 3, 2, 3.0, 367, 'Mercedes-Benz S-Class — флагман роскоши, технологий и комфорта, задающий стандарты премиального автомобилестроения. Его солидный, но динамичный экстерьер излучает авторитет, а интерьер поражает вниманием к деталям: высококачественные материалы, интеллектуальные системы помощи, продвинутый климат-контроль и уникальные опции вроде массажных кресел и ароматизации салона. Под капотом — мощные двигатели, включая гибридные версии, обеспечивающие плавный, но уверенныё ход. S-Class — это не просто автомобиль, а подвижная VIP-гостиная, где каждый момент на дороге становится удовольствием. Роскошь, ум, стиль — всё в одном.', 500, 250, NULL, '2025-05-30 22:43:31', '2025-05-30 22:44:24'),
(9, 'Performance', 8, 1, 1, 1, 2, 4, 4.0, 450, 'Cadillac CTS — американский спортседан, сочетающий мощь, элегантность и передовые технологии. Его резкие линии кузова, фирменная LED-оптика и агрессивный передний бампер подчёркивают динамичный характер. В салоне — сочетание спортивного стиля и премиального комфорта: кожаная отделка, современная приборная панель и продвинутый мультимедийный комплекс. Под капотом — бензиновые двигатели V6 и V8, обеспечивающие впечатляющую динамику и удовольствие от вождения. Cadillac CTS — это выражение индивидуальности для тех, кто ценит силу, стиль и свободу дороги.', 400, 230, '3d_models/equipment_9', '2025-05-30 22:45:49', '2025-05-30 22:45:49'),
(10, 'ESCALADE ESV LUXURY', 9, 6, 1, 2, 3, 4, 6.2, 412, 'Cadillac Escalade — символ американской роскоши, мощи и статуса. Этот внушительный люксовый SUV привлекает внимание с первого взгляда: массивные габариты, фирменная светодиодная оптика, хромированные детали и роскошный интерьер с кожаной отделкой, деревянными вставками и самым современным оборудованием. Под капотом — мощный 6,2-литровый V8 с компрессором, обеспечивающий уверенную тягу и динамику. Просторный салон, продвинутая мультимедийная система с огромным экраном, атмосфера комфорта и величия — всё это делает Escalade не просто автомобилем, а настоящим объектом восхищения на дороге. Роскошь, сила, индивидуальность — именно так можно описать этот легендарный внедорожник.', 300, 220, NULL, '2025-05-30 22:48:59', '2025-05-30 22:48:59'),
(11, 'Carrera 4s', 10, 4, 1, 3, 3, 2, 3.0, 450, 'Porsche 911 Carrera 4S — легендарный спорткар, в котором идеально слились динамика, технологии и эстетика. Его узнаваемый силуэт, широкие колёсные арки и фирменная оптика подчёркивают спортивный характер. Полный привод обеспечивает уверенное поведение на дороге, а турбированный оппозитный двигатель выдаёт потрясающую мощность и мгновенную реакцию на педаль газа. В салоне — идеальное сочетание функциональности, качества материалов и современных технологий. Carrera 4S — это не просто автомобиль, а воплощение свободы, скорости и стиля для тех, кто ценит истинное удовольствие от вождения.', 400, 280, '3d_models/equipment_11', '2025-05-30 22:51:58', '2025-05-30 22:51:58');

-- --------------------------------------------------------

--
-- Структура таблицы `equipment_colors`
--

CREATE TABLE `equipment_colors` (
  `id` bigint UNSIGNED NOT NULL,
  `equipment_id` bigint UNSIGNED NOT NULL,
  `color_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `equipment_colors`
--

INSERT INTO `equipment_colors` (`id`, `equipment_id`, `color_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 4, 4, NULL, NULL),
(5, 5, 5, NULL, NULL),
(6, 6, 5, NULL, NULL),
(7, 7, 5, NULL, NULL),
(8, 8, 5, NULL, NULL),
(9, 9, 4, NULL, NULL),
(10, 10, 6, NULL, NULL),
(11, 11, 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `favorite_equipments`
--

CREATE TABLE `favorite_equipments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `equipment_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `generations`
--

CREATE TABLE `generations` (
  `id` bigint UNSIGNED NOT NULL,
  `car_model_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_from` year NOT NULL,
  `year_to` year DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `generations`
--

INSERT INTO `generations` (`id`, `car_model_id`, `name`, `year_from`, `year_to`, `created_at`, `updated_at`) VALUES
(1, 1, 'F90', '2020', '2024', '2025-05-30 20:58:15', '2025-05-30 20:58:15'),
(2, 2, 'G07', '2018', '2020', '2025-05-30 20:58:34', '2025-05-30 20:58:34'),
(3, 3, 'R35', '2010', '2018', '2025-05-30 20:58:54', '2025-05-30 20:58:54'),
(4, 5, 'JZX100', '1996', '2002', '2025-05-30 20:59:43', '2025-05-30 20:59:43'),
(5, 6, 'X (S150)', '1995', '2008', '2025-05-30 21:00:16', '2025-05-30 21:00:16'),
(6, 7, 'IV (W205) Рестайлинг', '2018', '2023', '2025-05-30 21:01:02', '2025-05-30 21:01:02'),
(7, 8, 'VI (W222, C217) Рестайлинг', '2017', '2020', '2025-05-30 21:01:29', '2025-05-30 21:01:29'),
(8, 9, 'III', '2013', '2019', '2025-05-30 21:02:06', '2025-05-30 21:02:06'),
(9, 10, 'V Рестайлинг', '2024', NULL, '2025-05-30 21:02:39', '2025-05-30 21:02:39'),
(10, 12, 'VIII (992) Рестайлинг', '2024', NULL, '2025-05-30 21:03:20', '2025-05-30 21:03:20'),
(11, 13, 'XIV', '2020', '2024', '2025-05-30 21:03:48', '2025-05-30 21:03:48'),
(12, 14, 'III Рестайлинг', '2014', '2019', '2025-05-30 21:04:22', '2025-05-30 21:04:22'),
(13, 15, 'I Рестайлинг (NG)', '2022', NULL, '2025-05-30 21:04:53', '2025-05-30 21:04:53'),
(14, 16, 'I Рестайлинг', '2021', NULL, '2025-05-30 21:05:19', '2025-05-30 21:05:19'),
(15, 17, 'IV (A8) Рестайлинг', '2024', NULL, '2025-05-30 21:05:45', '2025-05-30 21:05:45'),
(16, 18, 'I Рестайлинг', '2021', NULL, '2025-05-30 21:06:05', '2025-05-30 21:06:05'),
(17, 19, 'I Рестайлинг 2', '2016', '2020', '2025-05-30 21:06:39', '2025-05-30 21:06:39'),
(18, 20, 'X Рестайлинг', '2011', '2015', '2025-05-30 21:07:11', '2025-05-30 21:07:11'),
(19, 4, 'N32 II Рестайлинг', '2010', '2015', '2025-05-30 21:13:04', '2025-05-30 21:13:04');

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_02_202758_create_brands_table', 1),
(5, '2025_05_02_202758_create_countries_table', 1),
(6, '2025_05_02_202759_create_body_types_table', 1),
(7, '2025_05_02_202759_create_car_models_table', 1),
(8, '2025_05_02_202759_create_generations_table', 1),
(9, '2025_05_02_202800_create_drive_types_table', 1),
(10, '2025_05_02_202800_create_engine_types_table', 1),
(11, '2025_05_02_202800_create_transmission_types_table', 1),
(12, '2025_05_02_202801_create_colors_table', 1),
(13, '2025_05_02_205006_create_equipment_table', 1),
(14, '2025_05_02_205255_create_equipment_colors_table', 1),
(15, '2025_05_03_111900_create_branches_table', 1),
(16, '2025_05_05_122932_create_cars_table', 1),
(17, '2025_05_05_123009_create_car_images_table', 1),
(18, '2025_05_10_195533_create_bookings_table', 1),
(19, '2025_05_11_095756_create_favorite_equipments_table', 1),
(20, '2025_05_11_095917_create_notifications_table', 1),
(21, '2025_05_30_161235_add_manager_id_to_bookings_table', 1),
(22, '2025_05_31_032445_update_appointment_date_nullable_in_bookings_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `car_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('NpVTnFVMSd3gLqyyyPr2wAYIwbvZxLNaHHWGmxzI', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoienZsZGpJYmFFU28xbnQzcmVoU0tOYVp5YmhNSVhrcDFFQXNQd3I2UiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9tYW5hZ2VycyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1749173513);

-- --------------------------------------------------------

--
-- Структура таблицы `transmission_types`
--

CREATE TABLE `transmission_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `transmission_types`
--

INSERT INTO `transmission_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Механическая', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(2, 'Автоматическая', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(3, 'Роботизированная', '2025-05-30 20:39:01', '2025-05-30 20:39:01'),
(4, 'Вариатор', '2025-05-30 20:39:01', '2025-05-30 20:39:01');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('superadmin','admin','manager','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '8 111 111 11 11', '$2y$12$vnPEJYjLallXfEGMp1VIpuvtknHbDT8utjw8Fm2PgzHtyIQ/gjXDq', 'admin', NULL, NULL, '2025-05-30 20:40:31', '2025-05-30 20:40:31'),
(2, 'Кирилл', 'user@gmail.com', '8 222 222 22 22', '$2y$12$6Fk769nVsRQ2PULf1txgGO7P6lmDo.OYcZ0cPEDE6Z.NXHon/KY4e', 'user', '2025-06-17 01:31:05', NULL, '2025-06-06 01:30:58', '2025-06-06 01:30:58'),
(3, 'manager', 'manager@gmail.com', '8 333 333 33 33', '$2y$12$n30VqKLjUZIyXuytHK3TceniEOfrlBNK/fjvFybDo9PqbBNVwJmbS', 'manager', NULL, NULL, '2025-06-06 01:31:52', '2025-06-06 01:31:52');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `body_types`
--
ALTER TABLE `body_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_car_id_status_index` (`car_id`,`status`),
  ADD KEY `bookings_manager_id_foreign` (`manager_id`);

--
-- Индексы таблицы `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Индексы таблицы `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Индексы таблицы `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cars_vin_unique` (`vin`),
  ADD KEY `cars_equipment_id_foreign` (`equipment_id`),
  ADD KEY `cars_branch_id_foreign` (`branch_id`),
  ADD KEY `cars_color_id_foreign` (`color_id`);

--
-- Индексы таблицы `car_images`
--
ALTER TABLE `car_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_images_car_id_foreign` (`car_id`);

--
-- Индексы таблицы `car_models`
--
ALTER TABLE `car_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_models_brand_id_foreign` (`brand_id`);

--
-- Индексы таблицы `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `drive_types`
--
ALTER TABLE `drive_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `engine_types`
--
ALTER TABLE `engine_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_generation_id_foreign` (`generation_id`),
  ADD KEY `equipment_body_type_id_foreign` (`body_type_id`),
  ADD KEY `equipment_engine_type_id_foreign` (`engine_type_id`),
  ADD KEY `equipment_transmission_type_id_foreign` (`transmission_type_id`),
  ADD KEY `equipment_drive_type_id_foreign` (`drive_type_id`),
  ADD KEY `equipment_country_id_foreign` (`country_id`);

--
-- Индексы таблицы `equipment_colors`
--
ALTER TABLE `equipment_colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_colors_equipment_id_foreign` (`equipment_id`),
  ADD KEY `equipment_colors_color_id_foreign` (`color_id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `favorite_equipments`
--
ALTER TABLE `favorite_equipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorite_equipments_user_id_equipment_id_unique` (`user_id`,`equipment_id`),
  ADD KEY `favorite_equipments_equipment_id_foreign` (`equipment_id`);

--
-- Индексы таблицы `generations`
--
ALTER TABLE `generations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `generations_car_model_id_foreign` (`car_model_id`);

--
-- Индексы таблицы `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Индексы таблицы `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_car_id_foreign` (`car_id`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Индексы таблицы `transmission_types`
--
ALTER TABLE `transmission_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `body_types`
--
ALTER TABLE `body_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `cars`
--
ALTER TABLE `cars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `car_images`
--
ALTER TABLE `car_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `car_models`
--
ALTER TABLE `car_models`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `drive_types`
--
ALTER TABLE `drive_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `engine_types`
--
ALTER TABLE `engine_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `equipment_colors`
--
ALTER TABLE `equipment_colors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `favorite_equipments`
--
ALTER TABLE `favorite_equipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `generations`
--
ALTER TABLE `generations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `transmission_types`
--
ALTER TABLE `transmission_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cars_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cars_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);

--
-- Ограничения внешнего ключа таблицы `car_images`
--
ALTER TABLE `car_images`
  ADD CONSTRAINT `car_images_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `car_models`
--
ALTER TABLE `car_models`
  ADD CONSTRAINT `car_models_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_body_type_id_foreign` FOREIGN KEY (`body_type_id`) REFERENCES `body_types` (`id`),
  ADD CONSTRAINT `equipment_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `equipment_drive_type_id_foreign` FOREIGN KEY (`drive_type_id`) REFERENCES `drive_types` (`id`),
  ADD CONSTRAINT `equipment_engine_type_id_foreign` FOREIGN KEY (`engine_type_id`) REFERENCES `engine_types` (`id`),
  ADD CONSTRAINT `equipment_generation_id_foreign` FOREIGN KEY (`generation_id`) REFERENCES `generations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipment_transmission_type_id_foreign` FOREIGN KEY (`transmission_type_id`) REFERENCES `transmission_types` (`id`);

--
-- Ограничения внешнего ключа таблицы `equipment_colors`
--
ALTER TABLE `equipment_colors`
  ADD CONSTRAINT `equipment_colors_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipment_colors_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorite_equipments`
--
ALTER TABLE `favorite_equipments`
  ADD CONSTRAINT `favorite_equipments_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_equipments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `generations`
--
ALTER TABLE `generations`
  ADD CONSTRAINT `generations_car_model_id_foreign` FOREIGN KEY (`car_model_id`) REFERENCES `car_models` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
