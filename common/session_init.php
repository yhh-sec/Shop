<?php
// Session 初始化配置文件
// 这个文件必须在任何 session_start() 调用之前包含

// Session 安全配置
ini_set('session.cookie_httponly', 1); // 防止 XSS 攻击
ini_set('session.use_strict_mode', 1); // 防止会话固定攻击
ini_set('session.cookie_lifetime', 0); // 浏览器关闭时清除 cookie
ini_set('session.gc_maxlifetime', 1800); // Session 过期时间 30 分钟

// 设置 session 名称
session_name('SHOP_SESSION');

// 设置 session cookie 参数
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);
?>