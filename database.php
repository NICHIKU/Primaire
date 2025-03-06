<?php

require_once __DIR__ . '/config/db_config.php';

function get_database_connection() {
    $conn = new mysqli('localhost', 'root', 'root', 'primaire');

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    return $conn;
}

?>