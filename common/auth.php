<?php
// 全局认证文件，用于检查登录状态和会话管理

// 引入 session 初始化配置（必须在 session_start 之前）
require_once(dirname(__FILE__) . '/session_init.php');

// 启动session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * 检查用户是否已登录
 * @return bool
 */
function isLoggedIn() {
    // 检查必要的session变量是否存在
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
        return false;
    }
    
    // 检查会话是否过期（例如30分钟）
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        // 会话已过期，销毁session
        session_unset();
        session_destroy();
        return false;
    }
    
    // 更新最后活动时间
    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * 获取当前用户信息
 * @return array|null
 */
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}

/**
 * 退出登录
 */
function logout() {
    session_unset();
    session_destroy();
}

/**
 * 重定向到登录页面
 */
function redirectToLogin() {
    header("Location: ".BASE_URL."/modules/user/login.php");
    exit;
}

/**
 * 检查用户权限
 * @param int $requiredRole 所需最低角色权限（1=管理员, 2=商家, 3=普通用户）
 * @return bool
 */
function checkUserRole($requiredRole) {
    if (!isLoggedIn()) {
        return false;
    }
    
    return $_SESSION['role'] <= $requiredRole;
}
?>