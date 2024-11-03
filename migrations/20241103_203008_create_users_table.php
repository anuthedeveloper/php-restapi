<?php

function up(PDO $db) {
    $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
    ";
    $db->exec($sql);
}

function down(PDO $db) {
    $sql = "DROP TABLE IF EXISTS users;";
    $db->exec($sql);
}
