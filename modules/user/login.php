<?php
/*
 * @Author: hzxOnlineOk
 * @Date: 2025-12-15 13:02:05
 * @LastEditors: 
 * @LastEditTime: 2025-12-15 13:02:17
 * @Description: 请填写简介
 */

// 引入 session 初始化配置（必须在 session_start 之前）
require_once('../../common/session_init.php');

// 确保session已启动
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../../config/config.php');
require_once('../../common/common.php');

// 登录逻辑（⚠️ 故意引入SQL注入漏洞用于教学演示）
$error = '';
if ($_POST) {
    $username = $_POST['username'];
    $password = encryptPwd($_POST['password']);
    
    // ❌ 危险代码：直接拼接参数，无过滤，可注入（例：username输入 ' OR 1=1 # 直接登录）
    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $stmt = $pdo->query($sql);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // 存入session（角色+用户ID+用户名）
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // 设置session过期时间（例如30分钟）
        $_SESSION['last_activity'] = time();
        
        // 根据角色跳转对应后台（使用绝对路径）
        if ($user['role'] == 1) {
            // 管理员跳转
            $redirect_url = BASE_URL . "/modules/admin/seller_list.php";
            header("Location: " . $redirect_url);
            exit;
        } elseif ($user['role'] == 2) {
            // 商家跳转
            $redirect_url = BASE_URL . "/modules/seller/goods_list.php";
            header("Location: " . $redirect_url);
            exit;
        } else {
            // 普通用户跳转
            $redirect_url = BASE_URL . "/modules/index/index.php";
            header("Location: " . $redirect_url);
            exit;
        }
    } else {
        $error = '用户名或密码错误！';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>登录 - 游戏外挂商城</title>
    <link rel="stylesheet" href="../../static/css/style.css">
</head>
<body>
    <div class="login-box">
        <h2>用户登录</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="">
            <div class="form-item">
                <label>用户名：</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-item">
                <label>密码：</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-btn">
                <button type="submit">登录</button>
                <a href="register.php">还没账号？注册</a>
            </div>
        </form>
    </div>
</body>
</html>