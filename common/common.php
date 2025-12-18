<?php
// 权限判断函数（防止未登录访问后台）
function checkLogin($role = 3) {
    // 只在session未启动时才启动
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] < $role) {
        header("Location: ".BASE_URL."/modules/user/login.php");
        exit;
    }
}

// 密码加密（简单MD5，实际开发需加盐，此处为演示）
function encryptPwd($pwd) {
    return md5($pwd);
}
?>