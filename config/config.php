<?php
// 数据库配置（直接修改为自己的MySQL信息）
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // 默认root
define('DB_PWD', 'root');        // PHPStudy默认密码root（若修改过则对应修改）
define('DB_NAME', 'shop_db');    // 新建的数据库名

// 数据库连接（PDO简化版，便于调用）
$pdo = new PDO(
    "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
    DB_USER,
    DB_PWD,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// 系统路径常量
define('BASE_URL', 'http://10.4.7.137:8080'); // 新建的网站域名
?>