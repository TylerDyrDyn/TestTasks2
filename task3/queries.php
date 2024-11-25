<?php

$createDataBaseQuery = [
    // Создание таблицы comments


    "CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT Now()
) ENGINE=InnoDB;"

];


