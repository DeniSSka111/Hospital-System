-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 02 2025 г., 13:57
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `hospital`
--

-- --------------------------------------------------------

--
-- Структура таблицы `hospitalizations`
--

CREATE TABLE `hospitalizations` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `admitted_at` datetime NOT NULL,
  `discharged_at` datetime DEFAULT NULL,
  `department` varchar(255) NOT NULL,
  `bed` varchar(50) NOT NULL,
  `reason` text NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `treatment_plan` text DEFAULT NULL,
  `status` enum('активна','выписан','переведен') DEFAULT 'активна',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `hospitalizations`
--

INSERT INTO `hospitalizations` (`id`, `patient_id`, `admitted_at`, `discharged_at`, `department`, `bed`, `reason`, `diagnosis`, `treatment_plan`, `status`, `notes`, `created_at`) VALUES
(1, 1, '2024-01-15 10:30:00', '2024-01-25 14:00:00', 'Кардиология', '205-1', 'Острая боль в груди, одышка', 'Острый инфаркт миокарда', 'Медикаментозная терапия, мониторинг ЭКГ, постельный режим', 'выписан', 'Пациент стабилизирован, выписан с рекомендациями', '2025-10-01 16:52:24'),
(2, 19, '2024-01-20 08:15:00', NULL, 'Хирургия', '312-3', 'Аппендицит', 'Острый флегмонозный аппендицит', 'Экстренная аппендэктомия, антибиотикотерапия', 'активна', 'Послеоперационный период, наблюдение', '2025-10-01 16:52:24'),
(3, 20, '2024-02-01 14:20:00', '2024-02-10 11:30:00', 'Неврология', '108-2', 'Головокружение, нарушение координации', 'Острое нарушение мозгового кровообращения', 'Сосудистая терапия, ЛФК, физиотерапия', 'выписан', 'Состояние улучшилось, требуется амбулаторное наблюдение', '2025-10-01 16:52:24'),
(4, 21, '2024-02-05 09:00:00', NULL, 'Терапия', '415-1', 'Высокая температура, кашель', 'Двусторонняя пневмония', 'Антибиотики широкого спектра, ингаляции, инфузионная терапия', 'активна', 'Тяжелое состояние, кислородная поддержка', '2025-10-01 16:52:24'),
(5, 22, '2024-01-10 16:45:00', '2024-01-12 12:00:00', 'Травматология', '220-4', 'Перелом лучевой кости', 'Закрытый перелом дистального метаэпифиза лучевой кости', 'Репозиция, гипсовая иммобилизация', 'выписан', 'Контрольный осмотр через 2 недели', '2025-10-01 16:52:24'),
(6, 23, '2024-02-08 11:20:00', NULL, 'Гастроэнтерология', '305-2', 'Боли в животе, рвота', 'Острый панкреатит', 'Голод, холод, покой, инфузионная терапия, спазмолитики', 'активна', 'Проводится детоксикационная терапия', '2025-10-01 16:52:24'),
(7, 24, '2024-01-28 13:10:00', '2024-02-05 10:15:00', 'Эндокринология', '112-1', 'Слабость, жажда, учащенное мочеиспускание', 'Декомпенсация сахарного диабета', 'Коррекция доз инсулина, диетотерапия, обучение', 'выписан', 'Достигнута компенсация углеводного обмена', '2025-10-01 16:52:24');

-- --------------------------------------------------------

--
-- Структура таблицы `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `medication_id` int(11) NOT NULL,
  `batch_number` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(10,2) NOT NULL,
  `expiry_date` date NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `received_date` date NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` enum('активно','использовано','списано','заблокировано') DEFAULT 'активно',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `inventory`
--

INSERT INTO `inventory` (`id`, `medication_id`, `batch_number`, `quantity`, `unit_price`, `expiry_date`, `supplier_id`, `received_date`, `location`, `status`, `notes`, `created_at`) VALUES
(1, 1, 'AMX-2301-01', 150, 450.50, '2025-06-30', 1, '2024-01-15', 'Стеллаж A-1', 'активно', NULL, '2025-10-01 17:43:01'),
(2, 1, 'AMX-2302-01', 75, 455.00, '2025-07-15', 1, '2024-02-01', 'Стеллаж A-1', 'активно', NULL, '2025-10-01 17:43:01'),
(3, 2, 'NUR-2401-01', 320, 280.75, '2025-09-20', 2, '2024-01-10', 'Стеллаж B-2', 'активно', NULL, '2025-10-01 17:43:01'),
(4, 2, 'NUR-2312-02', 180, 275.00, '2025-08-15', 2, '2023-12-05', 'Стеллаж B-2', 'активно', NULL, '2025-10-01 17:43:01'),
(5, 3, 'ENL-2312-01', 95, 120.30, '2025-03-15', 3, '2023-12-20', 'Стеллаж C-1', 'активно', NULL, '2025-10-01 17:43:01'),
(6, 4, 'MET-2401-01', 180, 85.90, '2025-10-10', 4, '2024-01-25', 'Стеллаж D-3', 'активно', NULL, '2025-10-01 17:43:01'),
(7, 5, 'ASP-2311-01', 250, 320.00, '2025-05-30', 1, '2023-11-15', 'Стеллаж E-2', 'активно', NULL, '2025-10-01 17:43:01'),
(8, 5, 'ASP-2402-01', 150, 325.50, '2025-07-20', 1, '2024-02-10', 'Стеллаж E-2', 'активно', NULL, '2025-10-01 17:43:01'),
(9, 6, 'LOS-2402-01', 60, 190.45, '2025-08-25', 2, '2024-02-05', 'Стеллаж F-1', 'активно', NULL, '2025-10-01 17:43:01'),
(10, 7, 'INS-2310-01', 45, 1250.00, '2024-09-30', 4, '2023-10-12', 'Холодильник 1', 'активно', NULL, '2025-10-01 17:43:01'),
(11, 8, 'SAL-2401-01', 35, 680.20, '2025-07-18', 3, '2024-01-18', 'Стеллаж G-4', 'активно', NULL, '2025-10-01 17:43:01'),
(12, 9, 'OME-2312-01', 120, 95.60, '2025-04-22', 1, '2023-12-28', 'Стеллаж H-2', 'активно', NULL, '2025-10-01 17:43:01'),
(13, 10, 'PAR-2401-01', 25, 145.80, '2025-06-15', 2, '2024-01-30', 'Стеллаж I-3', 'активно', NULL, '2025-10-01 17:43:01'),
(14, 11, 'CET-2401-01', 180, 210.35, '2025-11-30', 4, '2024-02-10', 'Стеллаж J-1', 'активно', NULL, '2025-10-01 17:43:01'),
(15, 12, 'AML-2401-01', 85, 135.75, '2025-10-05', 5, '2024-01-20', 'Стеллаж K-2', 'активно', NULL, '2025-10-01 17:43:01'),
(16, 13, 'LEV-2312-01', 45, 89.90, '2025-02-28', 3, '2023-12-15', 'Стеллаж L-1', 'активно', NULL, '2025-10-01 17:43:01'),
(17, 14, 'AZI-2401-01', 90, 420.00, '2025-08-15', 4, '2024-01-22', 'Стеллаж M-3', 'активно', NULL, '2025-10-01 17:43:01'),
(18, 15, 'VAL-2401-01', 220, 65.50, '2025-12-31', 2, '2024-01-08', 'Стеллаж N-4', 'активно', NULL, '2025-10-01 17:43:01');

-- --------------------------------------------------------

--
-- Структура таблицы `medications`
--

CREATE TABLE `medications` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `generic_name` varchar(255) DEFAULT NULL,
  `form` enum('таблетки','капсулы','сироп','инъекции','мазь','капли','спрей','порошок') DEFAULT 'таблетки',
  `strength` varchar(100) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `atc_code` varchar(50) DEFAULT NULL,
  `prescription_required` tinyint(1) DEFAULT 1,
  `storage_temperature` enum('комнатная','холодильник','морозильник') DEFAULT 'комнатная',
  `min_stock_level` int(11) DEFAULT 10,
  `max_stock_level` int(11) DEFAULT 100,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `medications`
--

INSERT INTO `medications` (`id`, `name`, `generic_name`, `form`, `strength`, `manufacturer`, `barcode`, `atc_code`, `prescription_required`, `storage_temperature`, `min_stock_level`, `max_stock_level`, `is_active`, `created_at`) VALUES
(1, 'Амоксиклав', 'Амоксициллин/клавулановая кислота', 'таблетки', '625 мг', 'Sandoz', '3856789012345', 'J01CR02', 1, 'комнатная', 50, 200, 1, '2025-10-01 17:43:01'),
(2, 'Нурофен', 'Ибупрофен', 'таблетки', '200 мг', 'Reckitt Benckiser', '3856789012346', 'M01AE01', 0, 'комнатная', 100, 500, 1, '2025-10-01 17:43:01'),
(3, 'Эналаприл', 'Эналаприл', 'таблетки', '5 мг', 'Gedeon Richter', '3856789012347', 'C09AA02', 1, 'комнатная', 30, 150, 1, '2025-10-01 17:43:01'),
(4, 'Метформин', 'Метформин', 'таблетки', '500 мг', 'Berlin-Chemie', '3856789012348', 'A10BA02', 1, 'комнатная', 40, 200, 1, '2025-10-01 17:43:01'),
(5, 'Аспирин Кардио', 'Ацетилсалициловая кислота', 'таблетки', '100 мг', 'Bayer', '3856789012349', 'B01AC06', 0, 'комнатная', 80, 400, 1, '2025-10-01 17:43:01'),
(6, 'Лозартан', 'Лозартан', 'таблетки', '50 мг', 'Teva', '3856789012350', 'C09CA01', 1, 'комнатная', 25, 120, 1, '2025-10-01 17:43:01'),
(7, 'Инсулин НовоРапид', 'Инсулин аспарт', 'инъекции', '100 ЕД/мл', 'Novo Nordisk', '3856789012351', 'A10AB05', 1, 'холодильник', 20, 80, 1, '2025-10-01 17:43:01'),
(8, 'Сальбутамол', 'Сальбутамол', 'спрей', '100 мкг/доза', 'GlaxoSmithKline', '3856789012352', 'R03AC02', 1, 'комнатная', 15, 60, 1, '2025-10-01 17:43:01'),
(9, 'Омепразол', 'Омепразол', 'капсулы', '20 мг', 'KRKA', '3856789012353', 'A02BC01', 1, 'комнатная', 35, 180, 1, '2025-10-01 17:43:01'),
(10, 'Парацетамол', 'Парацетамол', 'сироп', '120 мг/5 мл', 'Фармстандарт', '3856789012354', 'N02BE01', 0, 'комнатная', 10, 50, 1, '2025-10-01 17:43:01'),
(11, 'Цетрин', 'Цетиризин', 'таблетки', '10 мг', 'Dr. Reddy\'s', '3856789012355', 'R06AE07', 0, 'комнатная', 45, 220, 1, '2025-10-01 17:43:01'),
(12, 'Амлодипин', 'Амлодипин', 'таблетки', '10 мг', 'Gedeon Richter', '3856789012356', 'C08CA01', 1, 'комнатная', 30, 150, 1, '2025-10-01 17:43:01'),
(13, 'Левомицетин', 'Хлорамфеникол', 'капли', '0.25%', 'Фармстандарт', '3856789012357', 'S01AA01', 1, 'комнатная', 20, 100, 1, '2025-10-01 17:43:01'),
(14, 'Азитромицин', 'Азитромицин', 'капсулы', '500 мг', 'Pfizer', '3856789012358', 'J01FA10', 1, 'комнатная', 25, 120, 1, '2025-10-01 17:43:01'),
(15, 'Валерьянка', 'Экстракт валерианы', 'таблетки', '20 мг', 'Фармстандарт', '3856789012359', 'N05CM09', 0, 'комнатная', 60, 300, 1, '2025-10-01 17:43:01');

-- --------------------------------------------------------

--
-- Структура таблицы `outpatient_visits`
--

CREATE TABLE `outpatient_visits` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `visit_at` datetime NOT NULL,
  `doctor` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `complaint` text NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `prescription` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `visit_type` enum('первичный','повторный','консультация','процедура') DEFAULT 'первичный',
  `status` enum('завершен','запланирован','отменен') DEFAULT 'завершен',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `outpatient_visits`
--

INSERT INTO `outpatient_visits` (`id`, `patient_id`, `visit_at`, `doctor`, `department`, `complaint`, `diagnosis`, `prescription`, `recommendations`, `visit_type`, `status`, `notes`, `created_at`) VALUES
(1, 1, '2024-02-12 09:30:00', 'Петров А.С.', 'Терапия', 'Насморк, боль в горле, температура 37.8', 'Острая респираторная вирусная инфекция', 'Парацетамол 500 мг 3 раза в день, обильное питье', 'Постельный режим, контроль температуры', 'первичный', 'завершен', NULL, '2025-10-01 16:52:24'),
(2, 19, '2024-02-12 10:15:00', 'Сидорова Е.В.', 'Кардиология', 'Повышение АД до 160/100, головная боль', 'Артериальная гипертензия II стадии', 'Эналаприл 5 мг 2 раза в день, амлодипин 5 мг 1 раз в день', 'Контроль АД 2 раза в день, ограничение соли', 'повторный', 'завершен', NULL, '2025-10-01 16:52:24'),
(3, 20, '2024-02-11 14:45:00', 'Козлов Д.М.', 'Неврология', 'Боли в спине, онемение правой ноги', 'Остеохондроз поясничного отдела, радикулопатия', 'Диклофенак 75 мг 2 раза в день, мидокалм 150 мг 2 раза в день', 'ЛФК, избегать подъема тяжестей, МРТ поясничного отдела', 'первичный', 'завершен', NULL, '2025-10-01 16:52:24'),
(4, 21, '2024-02-10 11:00:00', 'Иванова М.П.', 'Кардиология', 'Контрольный осмотр после выписки', 'Постинфарктный кардиосклероз', 'Аспирин кардио 100 мг, бисопролол 2.5 мг, аторвастатин 20 мг', 'Диета, дозированные физические нагрузки, контроль липидов через 3 месяца', 'повторный', 'завершен', NULL, '2025-10-01 16:52:24'),
(5, 22, '2024-02-12 16:20:00', 'Фролов С.Н.', 'Неврология', 'Головные боли, шум в ушах', 'Дисциркуляторная энцефалопатия', 'Винпоцетин 10 мг 3 раза в день, циннаризин 25 мг 3 раза в день', 'Контроль АД, УЗДГ брахиоцефальных артерий', 'повторный', 'завершен', NULL, '2025-10-01 16:52:24'),
(6, 23, '2024-02-09 13:30:00', 'Громов П.А.', 'Травматология', 'Контрольный осмотр после снятия гипса', 'Состояние после перелома лучевой кости', 'Комплекс упражнений для разработки сустава', 'Физиотерапия, постепенное увеличение нагрузки', 'консультация', 'завершен', NULL, '2025-10-01 16:52:24'),
(7, 24, '2024-02-12 08:45:00', 'Никитина О.Л.', 'Гастроэнтерология', 'Боли в эпигастрии, изжога', 'Хронический гастрит, обострение', 'Омепразол 20 мг 2 раза в день, алмагель по 1 ст.л. 4 раза в день', 'Диета №1, дробное питание, исключить острое, жареное', 'первичный', 'завершен', NULL, '2025-10-01 16:52:24'),
(8, 19, '2024-02-20 10:00:00', 'Семенов И.Р.', 'Хирургия', 'Контрольный осмотр после операции', NULL, NULL, NULL, 'повторный', 'запланирован', NULL, '2025-10-01 16:52:24'),
(9, 21, '2024-02-25 11:30:00', 'Васильева Т.М.', 'Терапия', 'Контрольный осмотр после пневмонии', NULL, NULL, NULL, 'повторный', 'запланирован', NULL, '2025-10-01 16:52:24'),
(10, 23, '2024-02-18 14:15:00', 'Морозов А.Б.', 'Гастроэнтерология', 'Контроль УЗИ брюшной полости', NULL, NULL, NULL, 'процедура', 'запланирован', NULL, '2025-10-01 16:52:24');

-- --------------------------------------------------------

--
-- Структура таблицы `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(10) UNSIGNED NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `dob` date NOT NULL,
  `gender` enum('M','F','Other') NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `insurance_number` varchar(100) DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `patients`
--

INSERT INTO `patients` (`patient_id`, `last_name`, `first_name`, `middle_name`, `dob`, `gender`, `phone`, `address`, `insurance_number`, `blood_type`, `allergies`, `created_at`) VALUES
(1, 'Иванов', 'Иван', 'Иванович', '1990-05-20', 'M', '+7-900-111-2233', 'г. Калининград, ул. Центральная, д.10', 'POLIS-12345', 'A+', 'Пыльца', '2025-10-01 13:59:53'),
(19, 'Петрова', 'Мария', 'Сергеевна', '1987-03-12', 'F', '+7-900-555-8877', 'г. Калининград, ул. Молодёжная, д.15', 'POLIS-67890', '0-', 'Нет', '2025-10-01 14:03:51'),
(20, 'Сидоров', 'Алексей', 'Николаевич', '1975-07-08', 'M', '+7-900-333-1122', 'г. Калининград, ул. Советская, д.22', 'POLIS-11111', 'B+', 'Антибиотики', '2025-10-01 14:03:51'),
(21, 'Кузнецова', 'Елена', 'Викторовна', '1995-11-25', 'F', '+7-900-777-5544', 'г. Калининград, ул. Балтийская, д.5', 'POLIS-22222', 'AB-', 'Цитрусовые', '2025-10-01 14:03:51'),
(22, 'Морозов', 'Дмитрий', 'Павлович', '2001-09-14', 'M', '+7-900-999-8888', 'г. Калининград, ул. Озерная, д.7', 'POLIS-33333', '0+', 'Нет', '2025-10-01 14:03:51'),
(23, 'Васильева', 'Анна', 'Игоревна', '1993-02-03', 'F', '+7-900-666-4411', 'г. Калининград, ул. Московская, д.9', 'POLIS-44444', 'A-', 'Мёд', '2025-10-01 14:03:51'),
(24, 'Новикова', 'Ирина', 'Павловна', '1980-10-25', 'F', '+79012223344', 'Калининград, ул. Карла Маркса, д.55', 'POLIS-12356', 'A+', 'Шоколад', '2025-10-01 14:37:46');

-- --------------------------------------------------------

--
-- Структура таблицы `restock_orders`
--

CREATE TABLE `restock_orders` (
  `id` int(11) NOT NULL,
  `medication_id` int(11) NOT NULL,
  `quantity_ordered` int(11) NOT NULL,
  `quantity_received` int(11) DEFAULT 0,
  `order_date` date NOT NULL,
  `expected_delivery` date DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `status` enum('создан','подтвержден','доставлен','отменен') DEFAULT 'создан',
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `restock_orders`
--

INSERT INTO `restock_orders` (`id`, `medication_id`, `quantity_ordered`, `quantity_received`, `order_date`, `expected_delivery`, `supplier_id`, `status`, `unit_price`, `total_amount`, `notes`, `created_at`) VALUES
(1, 3, 100, 0, '2024-02-12', '2024-02-19', 3, 'подтвержден', 125.00, 12500.00, 'Срочный заказ - низкий остаток', '2025-10-01 17:43:01'),
(2, 8, 50, 0, '2024-02-10', '2024-02-17', 3, 'доставлен', 690.00, 34500.00, 'Регулярная поставка', '2025-10-01 17:43:01'),
(3, 10, 30, 0, '2024-02-15', '2024-02-22', 2, 'создан', 150.00, 4500.00, 'Пополнение детских лекарств', '2025-10-01 17:43:01'),
(4, 13, 40, 0, '2024-02-14', '2024-02-21', 3, 'подтвержден', 95.00, 3800.00, 'Пополнение глазных капель', '2025-10-01 17:43:01'),
(5, 7, 25, 0, '2024-02-16', '2024-02-23', 4, 'создан', 1300.00, 32500.00, 'Заказ инсулина', '2025-10-01 17:43:01');

-- --------------------------------------------------------

--
-- Структура таблицы `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `medication_id` int(11) NOT NULL,
  `batch_number` varchar(100) DEFAULT NULL,
  `movement_type` enum('приход','расход','списание','перемещение','корректировка') DEFAULT 'приход',
  `quantity` int(11) NOT NULL,
  `reference_type` enum('поставка','назначение','списание','инвентаризация') DEFAULT 'поставка',
  `reference_id` int(11) DEFAULT NULL,
  `movement_date` datetime DEFAULT current_timestamp(),
  `performed_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `medication_id`, `batch_number`, `movement_type`, `quantity`, `reference_type`, `reference_id`, `movement_date`, `performed_by`, `notes`) VALUES
(1, 1, 'AMX-2301-01', 'приход', 200, 'поставка', 1, '2025-10-01 17:43:01', 1, 'Первоначальное поступление'),
(2, 1, 'AMX-2301-01', 'расход', 50, 'назначение', 1, '2025-10-01 17:43:01', 2, 'Назначения за январь'),
(3, 2, 'NUR-2401-01', 'приход', 400, 'поставка', 2, '2025-10-01 17:43:01', 1, 'Основная поставка'),
(4, 2, 'NUR-2401-01', 'расход', 80, 'назначение', 2, '2025-10-01 17:43:01', 2, 'Назначения за январь'),
(5, 3, 'ENL-2312-01', 'приход', 100, 'поставка', 3, '2025-10-01 17:43:01', 1, 'Поставка от БалтФарма'),
(6, 3, 'ENL-2312-01', 'расход', 5, 'назначение', 3, '2025-10-01 17:43:01', 2, 'Ежедневные назначения'),
(7, 4, 'MET-2401-01', 'приход', 200, 'поставка', 4, '2025-10-01 17:43:01', 1, 'Поставка от Фармакор'),
(8, 4, 'MET-2401-01', 'расход', 20, 'назначение', 4, '2025-10-01 17:43:01', 2, 'Назначения пациентам с СД'),
(9, 7, 'INS-2310-01', 'приход', 50, 'поставка', 4, '2025-10-01 17:43:01', 1, 'Поставка инсулина'),
(10, 7, 'INS-2310-01', 'расход', 5, 'назначение', 5, '2025-10-01 17:43:01', 2, 'Назначения пациентам с диабетом'),
(11, 8, 'SAL-2401-01', 'приход', 40, 'поставка', 3, '2025-10-01 17:43:01', 1, 'Поставка ингаляторов'),
(12, 8, 'SAL-2401-01', 'расход', 5, 'назначение', 6, '2025-10-01 17:43:01', 2, 'Назначения при астме'),
(13, 11, 'CET-2401-01', 'приход', 200, 'поставка', 4, '2025-10-01 17:43:01', 1, 'Поставка антигистаминных'),
(14, 11, 'CET-2401-01', 'расход', 20, 'назначение', 7, '2025-10-01 17:43:01', 2, 'Назначения при аллергии'),
(15, 5, 'ASP-2311-01', 'расход', 45, 'назначение', 8, '2025-10-01 17:43:01', 2, 'Назначения кардиологическим пациентам'),
(16, 6, 'LOS-2402-01', 'расход', 12, 'назначение', 9, '2025-10-01 17:43:01', 2, 'Назначения при гипертензии'),
(17, 9, 'OME-2312-01', 'расход', 25, 'назначение', 10, '2025-10-01 17:43:01', 2, 'Назначения гастроэнтеролога'),
(18, 11, 'CET-2401-01', 'расход', 18, 'назначение', 11, '2025-10-01 17:43:01', 2, 'Сезонные аллергии'),
(19, 12, 'AML-2401-01', 'расход', 15, 'назначение', 12, '2025-10-01 17:43:01', 2, 'Назначения терапевта'),
(20, 14, 'AZI-2401-01', 'расход', 8, 'назначение', 13, '2025-10-01 17:43:01', 2, 'Назначения при инфекциях'),
(21, 15, 'VAL-2401-01', 'расход', 35, 'назначение', 14, '2025-10-01 17:43:01', 2, 'Назначения при бессоннице');

-- --------------------------------------------------------

--
-- Структура таблицы `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `inn` varchar(20) DEFAULT NULL,
  `rating` enum('отлично','хорошо','удовлетворительно','плохо') DEFAULT 'хорошо',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`, `email`, `address`, `inn`, `rating`, `is_active`, `created_at`) VALUES
(1, 'ФармСнаб ООО', 'Иванов Петр Сергеевич', '+7-4012-111-2233', 'order@pharmsnab.ru', 'г. Калининград, ул. Промышленная, 15', '3901123456', 'отлично', 1, '2025-10-01 17:43:01'),
(2, 'МедТехника АО', 'Смирнова Ольга Владимировна', '+7-4012-222-3344', 'supply@medtech.ru', 'г. Калининград, пр. Мира, 28', '3901234567', 'хорошо', 1, '2025-10-01 17:43:01'),
(3, 'БалтФарма ООО', 'Козлов Дмитрий Иванович', '+7-4012-333-4455', 'info@baltpharma.ru', 'г. Калининград, ул. Портововая, 7', '3901345678', 'удовлетворительно', 1, '2025-10-01 17:43:01'),
(4, 'Фармакор ПАО', 'Никитина Елена Михайловна', '+7-495-444-5566', 'orders@pharmacor.ru', 'г. Москва, ул. Ленинградская, 42', '7701456789', 'отлично', 1, '2025-10-01 17:43:01'),
(5, 'ВитаФарм ЗАО', 'Громов Алексей Викторович', '+7-4012-555-6677', 'supplies@vitapharm.ru', 'г. Калининград, ул. Заводская, 33', '3901567890', 'хорошо', 1, '2025-10-01 17:43:01');

-- --------------------------------------------------------

--
-- Структура таблицы `write_offs`
--

CREATE TABLE `write_offs` (
  `id` int(11) NOT NULL,
  `medication_id` int(11) NOT NULL,
  `batch_number` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `reason` enum('просрочка','брак','повреждение','кража','прочее') DEFAULT 'просрочка',
  `write_off_date` date NOT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `write_offs`
--

INSERT INTO `write_offs` (`id`, `medication_id`, `batch_number`, `quantity`, `reason`, `write_off_date`, `approved_by`, `notes`, `created_at`) VALUES
(1, 7, 'INS-2305-01', 3, 'просрочка', '2024-01-20', 1, 'Партия с истекшим сроком годности', '2025-10-01 17:43:01'),
(2, 2, 'NUR-2308-01', 15, 'повреждение', '2024-02-01', 1, 'Упаковка повреждена при транспортировке', '2025-10-01 17:43:01'),
(3, 13, 'LEV-2309-01', 8, 'просрочка', '2024-01-25', 1, 'Просроченные капли', '2025-10-01 17:43:01'),
(4, 5, 'ASP-2306-01', 12, 'брак', '2024-02-05', 1, 'Дефект блистерной упаковки', '2025-10-01 17:43:01'),
(5, 9, 'OME-2307-01', 5, 'повреждение', '2024-01-30', 1, 'Разрыв коробки, нарушение герметичности', '2025-10-01 17:43:01'),
(6, 1, 'AMX-2205-01', 7, 'просрочка', '2024-01-18', 1, 'Просроченный антибиотик', '2025-10-01 17:43:01'),
(7, 4, 'MET-2303-01', 10, 'брак', '2024-02-08', 1, 'Несоответствие маркировки', '2025-10-01 17:43:01'),
(8, 8, 'SAL-2308-01', 3, 'повреждение', '2024-01-22', 1, 'Механическое повреждение ингалятора', '2025-10-01 17:43:01'),
(9, 10, 'PAR-2309-01', 5, 'просрочка', '2024-02-03', 1, 'Сироп с истекшим сроком', '2025-10-01 17:43:01');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `hospitalizations`
--
ALTER TABLE `hospitalizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `department` (`department`),
  ADD KEY `status` (`status`);

--
-- Индексы таблицы `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medication_id` (`medication_id`),
  ADD KEY `batch_number` (`batch_number`),
  ADD KEY `expiry_date` (`expiry_date`),
  ADD KEY `status` (`status`);

--
-- Индексы таблицы `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `name` (`name`),
  ADD KEY `atc_code` (`atc_code`),
  ADD KEY `manufacturer` (`manufacturer`);

--
-- Индексы таблицы `outpatient_visits`
--
ALTER TABLE `outpatient_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor` (`doctor`),
  ADD KEY `visit_at` (`visit_at`);

--
-- Индексы таблицы `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `insurance_number` (`insurance_number`);

--
-- Индексы таблицы `restock_orders`
--
ALTER TABLE `restock_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `medication_id` (`medication_id`),
  ADD KEY `status` (`status`),
  ADD KEY `order_date` (`order_date`);

--
-- Индексы таблицы `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medication_id` (`medication_id`),
  ADD KEY `movement_type` (`movement_type`),
  ADD KEY `movement_date` (`movement_date`);

--
-- Индексы таблицы `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `write_offs`
--
ALTER TABLE `write_offs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medication_id` (`medication_id`),
  ADD KEY `write_off_date` (`write_off_date`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `hospitalizations`
--
ALTER TABLE `hospitalizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `medications`
--
ALTER TABLE `medications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `outpatient_visits`
--
ALTER TABLE `outpatient_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `restock_orders`
--
ALTER TABLE `restock_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `write_offs`
--
ALTER TABLE `write_offs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`medication_id`) REFERENCES `medications` (`id`);

--
-- Ограничения внешнего ключа таблицы `restock_orders`
--
ALTER TABLE `restock_orders`
  ADD CONSTRAINT `restock_orders_ibfk_1` FOREIGN KEY (`medication_id`) REFERENCES `medications` (`id`),
  ADD CONSTRAINT `restock_orders_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Ограничения внешнего ключа таблицы `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`medication_id`) REFERENCES `medications` (`id`);

--
-- Ограничения внешнего ключа таблицы `write_offs`
--
ALTER TABLE `write_offs`
  ADD CONSTRAINT `write_offs_ibfk_1` FOREIGN KEY (`medication_id`) REFERENCES `medications` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
