<?php
//Запрос создает базу данных, и очищает ее если она существует, для того что бы избежать дублирования при перезагрузке страницы
$createDataBaseQuery = [
    "DROP TABLE IF EXISTS availabilities",
    "DROP TABLE  IF EXISTS products",
    "DROP TABLE  IF EXISTS stocks",
    "DROP TABLE  IF EXISTS categories ",

    "CREATE TABLE IF NOT EXISTS `categories` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",

    "INSERT INTO `categories` (`id`, `title`) VALUES
    (1, 'Электроника'),
    (2, 'Бытовая техника'),
    (5, 'Аксессуары'),
    (6, 'Расходные материалы'),
    (9, 'Мебель'),
    (10, 'Товары для дачи')",

    "CREATE TABLE IF NOT EXISTS `products` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `category_id` int(11) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",

    "INSERT INTO `products` (`id`, `title`, `category_id`) VALUES
    (1, 'Телевизор LG', 1),
    (2, 'Смартфон Samsung', 1),
    (5, 'Микроволновая печь Redmond', 2),
    (6, 'Кухонная вытяжка Elica', 2),
    (9, 'Кабель питания HDMI', 6),
    (10, 'Сетевой фильтр', 6)",

    "CREATE TABLE IF NOT EXISTS `stocks` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",

    "INSERT INTO `stocks` (`id`, `title`) VALUES
    (1, 'Главный склад'),
    (2, 'Склад на Невской'),
    (5, 'Склад на Бакинской')",

    "CREATE TABLE IF NOT EXISTS `availabilities` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `amount` int(11) NOT NULL,
        `product_id` int(11) NOT NULL,
        `stock_id` int(11) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",

    "INSERT INTO `availabilities` (`id`, `amount`, `product_id`, `stock_id`) VALUES
    (1, 3, 1, 1),
    (2, 2, 1, 5),
    (5, 5, 2, 1),
    (6, 2, 5, 5),
    (9, 1, 6, 1),
    (10, 1, 6, 5)",
    
];

//Основной запрос рещающий тестовое задание, остальные запросы в коде нужны для демонстрации таблицы до и после применения этого запроса
//Запрос очищает базу данных от неиспользуемых данных
$cleanDataQuery = [
    "DELETE FROM stocks
    WHERE id NOT IN (SELECT  stock_id FROM availabilities);",

    "DELETE FROM products
    WHERE id NOT IN (SELECT  product_id FROM availabilities);",

    "DELETE FROM categories
    WHERE id NOT IN (SELECT  category_id FROM products);",
];
?>