<?php
/**
 * Database Configuration and Connection Handler
 * Raman Group - Construction, Interior & Fabrication Services
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'buildcraft_db');
define('DB_CHARSET', 'utf8mb4');

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Attempt to connect without dbname to check if DB exists or create notice
            try {
                $dsn_no_db = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
                $tmp_pdo = new PDO($dsn_no_db, DB_USER, DB_PASS, $options);
                $tmp_pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $ex) {
                die("<div style='font-family:sans-serif; padding:40px; text-align:center;'>
                        <h2 style='color:#e74c3c;'>Database Connection Failed</h2>
                        <p>Could not connect to MySQL database <strong>" . DB_NAME . "</strong>.</p>
                        <p>Please ensure MySQL service is running in XAMPP and import <code>database.sql</code> into phpMyAdmin.</p>
                        <p style='color:#7f8c8d;'><small>Error: " . htmlspecialchars($ex->getMessage()) . "</small></p>
                    </div>");
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}

function getDB() {
    return Database::getInstance();
}
