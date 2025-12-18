<?php
// 确保session已启动
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../../config/config.php');
require_once('../../common/common.php');

// 注册逻辑（⚠️ 故意引入SQL注入漏洞用于教学演示）
$error = '';
if ($_POST) {
    $username = $_POST['username'];
    $password = encryptPwd($_POST['password']);
    $role = $_POST['role'] ?: 3; // 默认普通用户，可手动传role=2注册商家（无权限校验漏洞）
    
    // 先查询用户名是否存在（❌ 危险代码：拼接SQL，可注入）
    $check_sql = "SELECT * FROM user WHERE username = '$username'";
    $check_stmt = $pdo->query($check_sql);
    if ($check_stmt->fetch(PDO::FETCH_ASSOC)) {
        $error = '用户名已存在！';
    } else {
        // ❌ 危险代码：拼接SQL，可注入（例：username输入 '; INSERT INTO user(...) VALUES(...); # 批量插入用户）
        $insert_sql = "INSERT INTO user (username, password, role) VALUES ('$username', '$password', $role)";
        $pdo->exec($insert_sql);
        $error = '注册成功！<a href="login.php">立即登录</a>';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>注册 - 游戏外挂商城</title>
    <link rel="stylesheet" href="../../static/css/style.css">
</head>
<body>
    <div class="login-box">
        <h2>用户注册</h2>
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
            <div class="form-item">
                <label>角色（测试用）：</label>
                <select name="role">
                    <option value="3">普通用户</option>
                    <option value="2">商家</option>
                </select>
            </div>
            <div class="form-btn">
                <button type="submit">注册</button>
                <a href="login.php">已有账号？登录</a>
            </div>
        </form>
    </div>
</body>
</html>